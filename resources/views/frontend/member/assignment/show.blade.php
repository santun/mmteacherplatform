@extends('frontend.layouts.default')
@section('title', __('Assignment Detail'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white">
    <!--<canvas class="constellation" data-radius="0"></canvas>-->
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                   <h1>{{ __('Assignment Detail')}}</h1>
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
                    <h1>{{ __('Assignment Detail') }}</h1>                    

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pt-4">
                                    <a href="{{route('member.course.show', $assignment->course_id)}}#nav-assignment" class="pull-right">@lang('Go To Assignments')</a>
                                    <table class="table table-bordered table-vcenter dataTable no-footer">
                                        <tr>
                                            <td>@lang('Course Title')</td>
                                            <td>{{$assignment->course->title}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('Assignment Title')</td>
                                            <td>{{$assignment->title}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('Assignment Instruction')</td>
                                            <td>{!!$assignment->description!!}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('Attachement PDF')</td>
                                            <td>
                                                <div style="padding: 10px 0px;">
                                                    @foreach($assignment->getMedia('assignment_attached_file') as $resource)
                                                        <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    </table>     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        </div>
    </section>
</main>

@stop

@section('css')
@parent
@endsection

@section('script')
@parent
    <script src="https://unpkg.com/vee-validate@latest"></script>
<script>
$(document).ready(function() {

});
</script>

<script>

        new Vue({
            el: '#assignment_root',
            data: {
                messages: {

                },
            },
            //components: [commodity_component],

            mounted() {
            },
            methods: {
                validateBeforeSubmit: function(e) {
                    this.$validator.validateAll().then((result) => {
                        console.log(result)

                        if (result) {
                            // eslint-disable-next-line
                            return true;
                        }
                        e.preventDefault();
                    });
                }
            }

        });
        </script>
    @endsection
