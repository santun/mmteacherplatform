<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\SubjectRepository;
use App\Models\Resource;
use App\Models\LinkReport;
use App\Models\Contact;
use App\Models\ArticleCategory;
use App\User;
use App\Repositories\CollegeRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\YearRepository;

class ListingController extends Controller
{
    public function getArticleCategories()
    {
        $posts = ArticleCategory::where('published', '=', 1)
            ->get()->pluck('title', 'id');


        $list = [];

        foreach ($posts as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }

    public function getAccessibleRights()
    {
        $types = User::TYPES;

        $list = [];

        foreach ($types as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }

    public function getReportTypes()
    {
        $types = LinkReport::TYPES;

        $list = [];

        foreach ($types as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }

    public function getRegionsAndStates()
    {
        $types = Contact::REGIONS_STATES;

        $list = [];

        foreach ($types as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }

    public function getUserTypes()
    {
        $types = UserRepository::getUserTypes(false);

        $list = [];

        foreach ($types as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }

    public function getNotificationChannel()
    {
        return response()->json([
            ['id' => 'sms', 'title' => 'SMS'],
            ['id' => 'email', 'title' => 'Email (Default)']
            ]);
    }

    public function getResourceFormats()
    {
        $formats = [];

        foreach (Resource::RESOURCE_FORMATS as $key => $value) {
            $formats[] = ['id' => $key, 'title' => __($value)];
        }

        return $formats;
        // return response()->json(Resource::RESOURCE_FORMATS);
    }

    public function getSubjects()
    {
        return response()->json(SubjectRepository::getPublishedApiItemList());
    }

    public function getCollege()
    {
        $colleges = CollegeRepository::getItemList(false);

        $list = [];

        foreach ($colleges as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }

    public function getYearofTeaching()
    {
        $years = YearRepository::getItemList(false);

        $list = [];

        foreach ($years as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }
}
