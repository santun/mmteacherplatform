<?php

namespace App\Http\Resources;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'cover_image' => get_course_cover_image($this->resource),
            'url_link' => $this->url_link,
            'course_category' => $this->category->name,
            'level' => Course::LEVELS[$this->level_id],
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y  h:m:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d-m-Y  h:m:s')
        ];
    }
}
