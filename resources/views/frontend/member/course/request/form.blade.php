@extends('frontend.layouts.default')
@section('title', __('Submit Course Request'))
@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white">
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                   {{--  <h1>{{ __('Submit Course Request') }}</h1> --}}
                </div>
            </div>
        </div>
    </header>
</div>
@endsection

@section('content')

<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container">
            <div class="row gap-y">

                <div class="col-md-3 mx-auto">
                @include('frontend.member.partials.sidebar')
                </div>

                <div class="col-md-9 mx-auto">

                    <h1>
                        {{ __('Submit Course Request') }}
                    </h1>

                    @if (isset($post)) {!! \Form::open(array('files' => true, 'method' => 'post', 'route' => array('member.course.save-submit-request',
                    $post->id) , 'class' => 'form-horizontal')) !!} @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="description" class="require">{{ __('Description') }}</label>
                                <textarea type="text" placeholder="Description.." name="description" id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{ old('description') }}</textarea>                                {!! $errors->first('description', '
                                <div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">


                            <div class="btn-group">
                                <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                            <a href="{{ route('member.course.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                            </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    </section>
</main>
@endsection
