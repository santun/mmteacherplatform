<div class="panel">
    @php
        $lecture_attached_url = '';
        if(file_exists(public_path().'/upload/'. $currentLecture->course->id . '-' . $currentLecture->uuid . '.pdf')) {
            $lecture_attached_url = $currentLecture->course->id . '-' . $currentLecture->uuid . '.pdf';
        }else{
            $lecture_attached_url = str_replace([' ', '?', '!', '~', '@', '#', '$', '%', '^', '&', '*', '(', ')', '?', '/','\'', '|', ',', '"'], '', $currentLecture->course->title . '-' . $currentLecture->lecture_title) . '.pdf';
        }
    @endphp
    <iframe
        src={{ asset('ViewerJS/#../upload/' . $lecture_attached_url) }}
{{--        src="http://localhost:8000/ViewerJS/#../upload/DatabaseManagementCourse-AdvencedOptimizationTechniques.pdf"--}}
            width="711"
        height="500"
        allowfullscreen
        webkitallowfullscreen
    >
    </iframe>
</div>


