{{--<audio src=""></audio>--}}
{{--@dd(asset($lecturesMedias->where('model_id', $currentLecture->id)->first()->getUrl())--}}
<figure>
    <figcaption>Listen to the T-Rex:</figcaption>
    <audio
        controls
        src="{{ $lecturesMedias->where('model_id', $currentLecture->id)->first()->getUrl() }}">
        Your browser does not support the
        <code>audio</code> element.
    </audio>
</figure>
