<?php

namespace App\Models;

use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;


class Course extends Model  implements HasMedia
{
	use Sortable, Unicodeable, HasMediaTrait, Sluggable;

	const BEGINNER_LEVEL = 1;
	const PRE_INTERMEDIATE_LEVEL = 2;
	const INTERMEDIATE_LEVEL = 3;
	const ADVANCE_LEVEL = 4;
	const PROFESSIONAL_LEVEL = 5;
	const LEVELS = [
        self::BEGINNER_LEVEL => 'Beginner',
        self::PRE_INTERMEDIATE_LEVEL => 'Pre-intermediate',
        self::INTERMEDIATE_LEVEL => 'Intermediate',
        self::ADVANCE_LEVEL => 'Advance',
        self::PROFESSIONAL_LEVEL => 'Professional',
    ];

    const APPROVAL_STATUS_PENDING = 0;
    const APPROVAL_STATUS_APPROVED = 1;
    const APPROVAL_STATUS_REJECTED = 2;

    const APPROVAL_STATUS = [
        self::APPROVAL_STATUS_PENDING => 'Pending',
        self::APPROVAL_STATUS_APPROVED => 'Approved',
        self::APPROVAL_STATUS_REJECTED => 'Rejected',
    ];

    const DOWNLOADABLE_OPTION_PARTIAL = 1;
    const DOWNLOADABLE_OPTION_COMPLETED = 2;
    const DOWNLOADABLE_OPTIONS = [
        self::DOWNLOADABLE_OPTION_PARTIAL => 'After Take Course',
        self::DOWNLOADABLE_OPTION_COMPLETED => 'After Completion'
    ];

    protected $fillable = [
            'title',
            'slug',
            'description',
            'cover_image',
            'url_link',
            'course_category_id',
            'level_id',
            'downloadable_option',
            'approval_status',
            'user_id',
            'approved_by',
            'approved_at',
            'is_published',
            'allow_edit',
            'is_locked',
    ];

    public $unicodeFields = [
        'title',
        'description',
    ];

    public $sortable = [
        'id',
        'title',
        'approved_by',
        'user_id',
        'created_at',
    ];

    protected $hidden = ['id'];

    public function privacies()
    {
        return $this->hasMany('App\Models\CoursePrivacy', 'course_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\CourseCategory', 'course_category_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function lecture()
    {
        return $this->hasMany('App\Models\Lecture')->orderBy('lecture_title', 'asc');
    }

    public function quizzes()
    {
        return $this->hasMany('App\Models\Quiz');
    }

    public function assignment()
    {
        return $this->hasMany('App\Models\Assignment');
    }

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeIsApproved($query)
    {
        return $query->where('approval_status', self::APPROVAL_STATUS_APPROVED);
    }

    public function scopeWithCategory($query, $course_category_id)
    {
        if ($course_category_id) {
            return $query->where('course_category_id', $course_category_id);
        } else {
            return $query;
        }
    }

    public function scopeWithLevel($query, $level_id)
    {
        if ($level_id) {
            return $query->where('level_id', $level_id);
        } else {
            return $query;
        }
    }

    public function scopeWithApprovalStatus($query, $approval_status)
    {
        if ($approval_status != null) {
            return $query->where('approval_status', $approval_status);
        } else {
            return $query;
        }
    }

    public function scopeWithUploadedBy($query, $uploaded_by)
    {
        if ($uploaded_by != null) {
            return $query->where('user_id', $uploaded_by);
        } else {
            return $query;
        }
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            $LIKE = config('cms.search_operator');
            $search = mm_search_string($search);
            $value = format_like_query($search);

            return $query->where('title', $LIKE, $value)
                        ->orWhere('description', $LIKE, $value);

            // Supports Keyword search
            // return $query->orWhereHas('keywords', function ($q) use ($search) {
            //     $q->whereIn('keyword', [$search]);
            // });
        }
        return $query;

    }

    public function getApprovalStatus()
    {
        if ($this->approval_status !== null) {
            return self::APPROVAL_STATUS[$this->approval_status];
        }

        return null;
    }

    public function getLevel()
    {
        return self::LEVELS[$this->level_id];
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    public function courseLearners()
    {
        return $this->belongsToMany(User::class, 'course_learners', 'course_id', 'user_id')
            ->withPivot('status');
    }

    public function getThumbnailPath()
    {
        return optional($this->getMedia('course_cover_image')->first())->getUrl('thumb');
    }

    public function getMediumPath()
    {
        return optional($this->getMedia('course_cover_image')->first())->getUrl('medium');
    }

    public function getImagePath()
    {
        return optional($this->getMedia('course_cover_image')->first())->getUrl('large');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('course_cover_image')
                ->singleFile()
                ->registerMediaConversions(function (Media $media) {
                    $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->nonQueued();

                    $this
                    ->addMediaConversion('bthumb')
                    ->width(300)
                    ->height(300)
                    ->nonQueued();

                    $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400)
                    ->nonQueued();

                    $this
                    ->addMediaConversion('large')
                    ->width(800)
                    ->height(800)
                    ->nonQueued();
                });
        $this->addMediaCollection('course_resource_file')
            ->singleFile();

    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
