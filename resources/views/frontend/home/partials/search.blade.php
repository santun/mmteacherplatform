<div class="container text-center">
    <div class="row">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="section-dialog text-white shadow-6" style="background: rgba(0, 0, 0, 0.3);">
            <h2>{{__('Ready to get started?') }}</h2>

            <div class="row mt-3">
                {{ Form::open(array('route' => 'elibrary.index', 'method' => 'get', 'class' => 'col-md-12 col-xl-12 input-glass mx-auto')) }}
                <div class="input-group input-group-lg1">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong class="fa fa-search"></strong></span>
                    </div>
                    <input title="Search" aria-label="Search" type="text" name="search" class="form-control" placeholder="Enter your keyword"
                        value="{{ request('search') }}">
                    <span class="input-group-append">
                        <button class="btn">{{ __('Search') }}</button>
                    </span>
                </div>
                <div class="mt-3 text-right">
                    <a href="{{ route('search.advanced') }}" class="text-blue">{{ __('Advanced Search') }}</a>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>

</div>
