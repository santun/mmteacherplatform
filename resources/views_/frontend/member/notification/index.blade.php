@extends('frontend.layouts.default')
@section('title', __('Notifications'))

@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">
    <!--<section class="section bg-gray overflow-hidden"> -->
	<section class="section pt-5 bg-gray overflow-hidden">
        <div class="container">
            <div class="row">

                <div class="col-md-3 mx-auto">
                    @include('frontend.member.partials.sidebar')
                </div>
                <div class="col-md-9 mx-auto">

                    <h1>{{ __('Notifications') }}</h1>

                    <table class="table table-striped">
                    @foreach($notifications as $notification)
                    <tr class="bd-highlight hover-shadow-2">
                        <td>
                            <a class="text-muted" href="{{ route('member.notification.show', $notification->id) }}">
                                @if ($notification->read_at == null)
                                <strong>{{ $notification->data['title'] }}</strong>
                                @else
                                {{ $notification->data['title'] }}
                                @endif
                            </a>
                        </td>
                        <td width="120">
                            {{ $notification->created_at->diffForHumans() }}
                        </td>
                        <td width="30">
                            <a class="text-danger" onclick="return confirm('Are you sure you want to delete?')" href="{{ route('member.notification.destroy', $notification->id) }}"><i class="ti-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    </table>

                    @if ($notifications)
                    <div>
                        {{ $notifications->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
</main>
@endsection
