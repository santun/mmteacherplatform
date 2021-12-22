<h4>Course Content</h4>
@foreach($lectures as $lecture)
    <div>
        <input type="checkbox" class="mt-2 mr-3"
               @if($userLectures->where('id', $lecture->id)->first())
                   checked
               @endif
               @if(isset($nextLecture))
                    @if($lecture->id != $currentLecture->id  and $lecture->id != $nextLecture->id)
                    disabled
                    @endif
               @endif
        />
        @if($lecturesMedias->where('model_id', $lecture->id)->first()->mime_type == 'application/pdf')
            <i class="fa fa-file-pdf-o mt-1"></i>
        @elseif($lecturesMedias->where('model_id', $lecture->id)->first()->mime_type == 'video/mp4')
            <i class="fa fa-play"></i>
        @elseif($lecturesMedias->where('model_id', $lecture->id)->first()->mime_type == 'audio/mpeg')
            <i class="fa fa-music"></i>
        @elseif($lecturesMedias->where('model_id', $lecture->id)->first()->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' || $lecturesMedias->where('model_id', $lecture->id)->first()->mime_type == "application/vnd.ms-powerpoint")
            <i class="fa fa-file-powerpoint-o mr-2"></i>
        @else
            <i class="fa fa-file mr2"></i>
        @endif
        <a href="{{ $userLectures->where('id', $lecture->id)->first() ? route('courses.learn-course', [$course, $lecture]) : '#' }}">
            {{ $lecture->lecture_title }}
        </a>
        @if($downloadOption == 1)
            <a href="{{ route('courses.download-lecture', $lecture) }}"><i class="ml-3 fa fa-download"></i></a>
        @elseif($downloadOption == 2)
            @if(\App\Repositories\CourseRepository::checkProgress($course, $userLectures) >= 100)
                <a href="{{ route('courses.download-lecture', $lecture) }}"><i class="ml-3 fa fa-download"></i></a>
            @endif
        @endif
    </div>
    @foreach($lecture->quizzes as $quiz)
        <div>
            <i class="fa fa-question-circle mr-2"></i>
            {{ $quiz->title }}
        </div>
    @endforeach
@endforeach

<h4 class="mt-5">Course Quizzes</h4>

@foreach($course->quizzes()->where('lecture_id', null)->get() as $quiz)
    <div>
        <a href="{{ route('quiz.show', $quiz->id) }}">
            <i class="fa fa-question-circle mr-2"></i>
            {{ $quiz->title }}
        </a>
    </div>
@endforeach

<h4 class="mt-5">Assignments</h4>

@foreach($course->assignment as $assignment)
    <div>
        @if($assignmentUser = auth()->user()->assignment_user->where('assignment_id', $assignment->id)->first())
            <a href="{{ route('courses.view-assignment-feedback', $assignmentUser) }}">{{ $assignment->title }}</a>
        @else
            <a href="{{ route('courses.view-assignment', $assignment) }}">{{ $assignment->title }}</a>
        @endif
    </div>
@endforeach

@if($course->getMedia('course_resource_file')->first())
    @if($downloadOption == 1)
        <h4 class="mt-5">Download Entire Course</h4>
        <a href="{{ route('courses.download-course', $course) }}" class="btn btn-outline-primary"><i class="ml-3 fa fa-download"></i> Download</a>
    @elseif($downloadOption == 2)
        @if(\App\Repositories\CourseRepository::checkProgress($course, $userLectures) >= 100)
            <h4>Download Entire Course</h4>
            <a href="{{ route('courses.download-course', $course) }}" class="btn btn-outline-primary"><i class="ml-3 fa fa-download"></i> Download</a>
        @endif
    @endif
@endif
