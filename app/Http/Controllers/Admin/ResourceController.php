<?php

namespace App\Http\Controllers\Admin;

use App\Models\Resource;
use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\LicenseRepository;
use App\Repositories\YearRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Http\Requests\RequestResource as Request;
use Spatie\MediaLibrary\Models\Media;

class ResourceController extends Controller
{
    protected $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;

        $this->middleware('permission:view_resource');
        $this->middleware('permission:add_resource', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_resource', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_resource', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->index(request());

        $subjects = SubjectRepository::getItemList();

        $formats = Resource::RESOURCE_FORMATS;
        $approvalStatus = Resource::APPROVAL_STATUS;
        $uploaded_by = UserRepository::getAllUploaders();

        return view('backend.resource.index', compact('posts', 'subjects', 'formats', 'approvalStatus', 'uploaded_by'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($format = Resource::IMAGE)
    {
        $subjects = SubjectRepository::getAllPublished();
        $licenses = LicenseRepository::getItemList();
        $years = YearRepository::getItemList(false);
        $userTypes = UserRepository::getTypesList(true);
        $formats = ['' => '- Select Format -'] + Resource::RESOURCE_FORMATS;
        $approvalStatus = Resource::APPROVAL_STATUS;
        $roles = RoleRepository::getRoleList();

        $keywords = [];
        $selectedKeywords = [];
        $otherKeywords = [];
        $selectedOtherKeywords = [];

        return view('backend.resource.form', compact(
            'subjects',
            'licenses',
            'format',
            'userTypes',
            'formats',
            'years',
            'roles',
            'keywords',
            'selectedKeywords',
            'otherKeywords',
            'selectedOtherKeywords',
            'approvalStatus'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.resource.create')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated. And you are ready to create a new resource.'
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.resource.edit', $id)
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnSaveClose')) {
            return redirect()->to($request->only('redirect_to'))
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.resource.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $lang = 'en')
    {
        $post = $this->repository->find($id);
        $subjects = SubjectRepository::getAllPublished();
        $licenses = LicenseRepository::getItemList();
        $userTypes = UserRepository::getTypesList(true);
        $formats = ['' => '- Select Format -'] + Resource::RESOURCE_FORMATS;
        $approvalStatus = Resource::APPROVAL_STATUS;
        $years = YearRepository::getItemList(false);
        $roles = RoleRepository::getRoleList();
        $format = $post->resource_format;
        $keywords = $post->keywordList();
        $otherKeywords = $post->keywordList('other');

        $selectedKeywords = $keywords->map(function ($key, $value) {
            return $value;
        });

        $selectedKeywords = $selectedKeywords->toArray();

        $selectedOtherKeywords = $otherKeywords->map(function ($key, $value) {
            return $value;
        });

        $selectedOtherKeywords = $selectedOtherKeywords->toArray();

        return view('backend.resource.form', compact(
            'subjects',
            'format',
            'post',
            //'tags',
            'licenses',
            'formats',
            'roles',
            'years',
            'userTypes',
            'keywords',
            'selectedKeywords',
            'otherKeywords',
            'selectedOtherKeywords',
            'approvalStatus'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request, $id);

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.resource.create')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated. And you are ready to create a new resource.'
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.resource.edit', $id)
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnSaveClose')) {
            return redirect($request->input('redirect_to'))
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.resource.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);

        $post->delete();
        /*
        $medias = Media::where('model_type', 'App\Models\Resource')->where('model_id', $id)->get();

        if (count($medias) > 0) {
            foreach ($medias as $media) {
                if ($media->getCustomProperty('uri')) {
                    $video_id = str_replace("/videos/", "", $media->getCustomProperty('uri'));
                    ResourceRepository::deleteVimeoVideo($video_id);
                }

                $media->delete();
            }
        }
        */

        return redirect()->back()
          ->with('success', 'Successfully deleted');
    }

    public function clone($id)
    {
        $post = Resource::findOrFail($id);

        $clone = $this->repository->cloneResource($id);

        return redirect()->route('admin.resource.edit', $clone->id)
        ->with('success', 'A new version of ' . $post->title . ' is successfully created. Please start uploading cover, preview and media file to finish.');
    }
}
