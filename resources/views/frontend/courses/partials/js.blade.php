<script>
    $(document).ready(function () {
        let courseCategories = [];
        let courseLevels = [];
        let keyword = '';
        let url = "{{ url('e-learning/courses') }}";
        ajaxCall(courseCategories, courseLevels, keyword, url);

        $('body').on('click', '.pagination li a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            ajaxCall(courseCategories, courseLevels, keyword, url);
        });

        $('.course-filter').on('change', function () {
            courseCategories = [];
            courseLevels = [];
            $('input[name="course_category"]:checked').each(function() {
                courseCategories.push(this.value);
            });
            $('input[name="course_level"]:checked').each(function() {
                courseLevels.push(this.value);
            });

            ajaxCall(courseCategories, courseLevels, keyword, url);
        });

        $('#keyword').keyup(function () {
            keyword = $('#keyword').val();
            ajaxCall(courseCategories, courseLevels, keyword, url);
        });

        function ajaxCall(courseCategories, courseLevels, keyword='', url) {
            // console.log(typeof courseCategories)
            // console.log(typeof courseLevels)
            // console.log(typeof keyword)

            if(typeof courseCategories === "object" && courseCategories.length !== 0){
                $('.e-learning-guid').css("display", "none");
            }else if(typeof courseLevels === "object" && courseLevels.length !== 0){                
                $('.e-learning-guid').css("display", "none");
            }else if(keyword !== ""){
                $('.e-learning-guid').css("display", "none");
            }else{
                $('.e-learning-guid').css("display", "block");
            }

            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                data: {courseCategories: courseCategories, courseLevels: courseLevels, keyword: keyword},
                beforeSend: function() {
                    $('.course-info').html(
                        '<div class="row"><div class="col"><h3>Loading...</h3></div></div>'
                    );
                },
                success: function(response) {

                    let courseInfo = [];
                    let paginationList = [];

                    if(response.data.length === 0) {
                        $('.pagination').css("display", "none");
                        $('.course-info').html(
                            '<div class="row">' +
                                '<div class="col"><h1>No Course Found!</h1></div>' +
                            '</div>'
                        );
                    } else {
                        $('.pagination').css("display", "flex");

                        let nextUrl = response.links.next;
                        let prevUrl = response.links.prev;

                        if(nextUrl) {
                            $('#next_url').attr('href', nextUrl);
                        }

                        if(prevUrl) {
                            $('#previous_url').attr('href', prevUrl);
                        } else {
                            $('#previous_url').attr('href', response.links.first)
                        }

                        for (let i = 0; i < response.data.length; i++) {
                            let courseUrl = "{{ url('/e-learning/courses') }}/" + response.data[i].slug;
                            courseInfo.push(
                                '<div class="border mt-1 p-2">\n' +
                                '            <div class="row">\n' +
                                '                <div class="col-md-4">\n' +
                                '                    <img src="' + response.data[i].cover_image + '"\n' +
                                '                         alt=""\n' +
                                '                         class="img course-img"\n' +
                                '                    >\n' +
                                '                </div>\n' +
                                '                <div class="col-md-6">\n' +
                                '                   <div class="">\n' +
                                '                       <h5><a href="' + courseUrl +'">' + response.data[i].title + '</a></h5>\n' +
                                '                       <p>'+ response.data[i].course_category +'</p>\n' +
                                '                       <p>'+ response.data[i].level +'</p>\n' +
                                '                </div>\n' +
                                '                </div>\n' +
                                '                <div class="col-md-2">\n' +
                                '                    <span class="font-size-12">Last Update</span>\n' + '<br>' +
                                '                    <span class="font-size-12">' + response.data[i].updated_at + '</span>\n' +
                                '                </div>\n' +
                                '            </div>\n' +
                                '        </div>'
                            );
                        }

                        for(let i = 1; i <= response.meta.last_page; i++) {
                            let pageId = 'page' + i;
                            let pageUrl = response.meta.path + '?page=' + i;

                            paginationList.push(
                                '<li class="page-item"><a class="page-link"'
                                + ' id=' + pageId +' href="' + pageUrl + '">' + i + '</a></li>'
                            );
                        }
                        $('.course-info').html(courseInfo);
                        $('.pagination-list').html(paginationList);

                        let currentPage = "page" + response.meta.current_page;
                        $('#' + currentPage).css('background-color', '#000000');
                    }
                },
                error: function (response) {
                    if(response.status === 500) {
                        $('.course-info').html(
                            '<div class="row"><div class="col"><h3>500 Server Error! Sorry, We Will Fix Error Soon, Please Try Again Later!</h3></div></div>'
                        );
                    } else {
                        $('.course-info').html(
                            '<div class="row"><div class="col"><h3>Please Check Your Connection And Try Later!</h3></div></div>'
                        );
                    }
                }

            });
        }
    });
</script>
