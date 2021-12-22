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
        <div class="text-grey">
            {{ $post->created_at->format('M d, Y')  }}
        </div>

        <div class="card-title lead-2">
            <a href="{{ url($post->path()) }}">{{ $post->title }}</a>
        </div>

        <p class="card-description">
            {{ str_replace('&nbsp;', ' ', str_limit(strip_tags($post->body), 100, '...')) }}
        </p>
    </div>
</div>
