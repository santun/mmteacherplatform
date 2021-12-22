<div class="card card-blog">

    <div class="card-image">
        <a href="{{ url($post->path()) }}">
            @if ($image_url = $post->getImagePath())
            <img class="shadow-2 rounded" src="{{ asset($image_url) }}" alt="{{ $post->title }}">
            @else
            <img class="shadow-2 rounded" src="{{ asset('assets/img/vector/3.png') }}" alt="{{ $post->title }}">
            @endif
        </a>
    </div>

    <div class="card-body">
        <div class="p-0">
            <div class="card-title lead-2 m-0 overflow-hidden"><a href="{{ url($post->path()) }}">{{ $post->title }}</a></div>
            <small class="m-0 text-black-50">
                            <span class="text-info">{{ title_case($post->getResourceFormat()) }}</span> - <em>{{ __('by') }}</em> {{ $post->author ?? '' }}
                        </small>
            <p>
                {{ str_limit(strip_tags($post->description), 100, '...') }}
            </p>
        </div>
    </div>
</div>
