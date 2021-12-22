<div class="card">
    <header class="card-header bg-white">
        <h4 class="card-title">
            <a href="{{route('member.assignment.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
        </h4>
    </header>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer" style="width: 100%;">
            <thead>
                <tr>
                    <th width="60">{{__('No#')}}</th>
                    <th>{{__('Assignment Title ')}}</th>
                    <th>{{__('Assignment Instruction')}}</th>
                    <th>{{__('Attached File') }}</th>
                    <th>{{__('Created At')}}</th>
                    <th width="150" class="text-center">{{__('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $key => $assignment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $assignment->title }}</td>
                    <td>{!! $assignment->description !!}</td>
                    <td>
                        @foreach($assignment->getMedia('assignment_attached_file') as $resource)
                            <a href="{{asset($resource->getUrl())}}" target="_blank"  class="">{{ $resource->file_name }}</a>
                            ({{ $resource->human_readable_size }})
                        @endforeach
                    </td>
                    <td>{{ $assignment->created_at ?? '' }} </td>
                    <td class="text-right table-options">
                        @php
                            $canEditAssignment = App\Repositories\AssignmentRepository::canEdit($assignment);
                        @endphp
                        <div class="btn-group btn-small">
                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.assignment.detail', [$assignment->id]) }}" data-provide="tooltip"
                                        title="Show"><i
                                        class=""></i>{{__('View Assignments')}}</a>
                        @if($canEdit && $canEditAssignment)
                                {{--<a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.assignment.show', [$assignment->id]) }}" data-provide="tooltip"
                                    title="Show"><i class="ti-eye"></i>  </a>--}}

                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.assignment.edit', [$assignment->id]) }}" data-provide="tooltip"
                                    title="Edit"><i class="ti-pencil"></i>  </a>

                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                {!! Form::open(array('route' => array('member.assignment.destroy', $assignment->id), 'method' => 'delete'
                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                                <i class="ti-trash"></i>
                                {{ __('Delete') }}
                                </button>
                                {!! Form::close() !!}
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