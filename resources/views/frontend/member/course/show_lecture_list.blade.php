<div class="card">
    <header class="card-header bg-white">
        <h4 class="card-title">
            <a href="{{route('member.lecture.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
        </h4>
    </header>
     <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer" style="width: 100%;">
        <thead>
            <tr>
                <th>@lang('No#')</th>
                <th>@lang('Lecture Title')</th>
                <th>@lang('Resource Link')</th>
                <th>@lang('Attached File')</th>
                <th>@lang('Description')</th>
                <th>@lang('Uploaded By')</th>
                <th>@lang('Created At')</th>
                <th width="150" class="text-center">@lang('Actions')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lectures as $key => $lecture)
            <tr>
                <td width="10">{{ $key + 1 }}</td>
                <td width="200">{{ $lecture->lecture_title }}</td>
                <td width="200">{{ $lecture->resource_link }}</td>
                <td width="100">
                    @foreach($lecture->getMedia('lecture_attached_file') as $resource)
                        <a href="{{asset($resource->getUrl())}}" target="_blank" class="">{{ $resource->file_name }} </a>
                                ({{ $resource->human_readable_size }})
                        {{--@if(in_array($resource->mime_type, ['application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.oasis.opendocument.presentation',
                            'application/vnd.oasis.opendocument.spreadsheet',
                            'application/vnd.oasis.opendocument.text',
                            'application/pdf',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'application/rtf',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            )
                            <div class="col-md-6 mb-md-0 mt-5">
                                <a href="https://docs.google.com/gview?url={{asset($resource->getUrl())}}&embedded=true" data-provide="lightbox" class="btn btn-primary btn-lg">Preview Before Download</a>
                            </div>
                        @endif --}}
                    @endforeach
                </td>
                <td width="200">{!! $lecture->description !!}</td>
                <td>{{ $lecture->user->name ?? '' }}</td>
                <td>{{ $lecture->created_at ?? '' }} </td>
                <td class="text-right table-options">
                    <div class="btn-group btn-small">                                                                
                        @if ($canEdit)
                        <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.lecture.edit', [$lecture->id]) }}" data-provide="tooltip" title="Edit"><i class="ti-pencil"></i> {{ __('Edit') }} </a>
                        <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            {!! Form::open(array('route' => array('member.lecture.destroy', $lecture->id), 'method' => 'delete'
                            , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                            <button data-provide="tooltip" style="cursor: pointer" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                            <i class="ti-trash"></i>
                            {{ __('Delete') }}
                            </button>
                            {!! Form::close() !!}
                            <a class="btn pr-2 pl-1 btn-outline" href="{{ route('member.quiz.create', [$course->id]).'?lecture_id='. $lecture->id}}" data-provide="tooltip" title="New Quiz" class="dropdown-item"><i class="ti-plus"></i> {{ __('New Quiz') }} </a>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="6"><div class="text-center">No records.</div></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>