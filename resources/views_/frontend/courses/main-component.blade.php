<div class="col-md-9 mt-5">
    <div class="row ml-2">
        <div class="course-category-tag d-flex float-left"></div>
    </div>
    <div>
        <section class="section e-learning-guid text-white overflow-hidden py-0 mb-5" style="background-image: url({{ asset('assets/img/bg/2.jpg') }}">
            <div class="container-fluid p-0">
              <div class="row">

                <div class="col-md-7 py-9" data-overlay="1">
                    <div class="col-12 mx-auto">
                      <h2 class="text-center">{{ __('How to use E-Learning') }}</h2>
                    <p class="lead text-center">
                        Click <a href="{{ url(LaravelLocalization::setLocale().'/'. $how_to_slug) }}" class="text-primary">here</a> to see how to use E-Learning.
                    </p>
                      <br><br>
                    </div>
                </div>

              </div>
            </div>
          </section>
    </div>

    <div class="course-info">
    </div>
    <div class="mt-3">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item prev-link">
                    <a class="page-link" id="previous_url" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <div class="d-flex pagination-list">
                </div>
                <li class="page-item">
                    <a class="page-link" id="next_url" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
