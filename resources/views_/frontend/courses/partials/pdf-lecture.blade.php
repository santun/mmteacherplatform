{{--<embed width="711" height="800" src="{{ asset($lecturesMedias->where('model_id', $currentLecture->id)->first()->getUrl()) }}">--}}

<div class="panel">
    <iframe
        src={{ str_replace(config('app.url'), config('app.url') . '/ViewerJS/#..', asset($lecturesMedias->where('model_id', $currentLecture->id)->first()->getUrl())) }}
{{--        src="http://localhost:8000/ViewerJS/#../mpu.pdf"--}}
        width="711"
        height="700"
        allowfullscreen
        webkitallowfullscreen
    >
    </iframe>
</div>
