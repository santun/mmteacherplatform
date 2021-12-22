<?php
// Member Routes
Route::namespace('Member')
    ->middleware(['auth',
        'isApproved',
        'isVerified',
        // 'isBlocked'
        // 'isAdmin', 'isBlocked'
    ])
    ->name('member.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController')->name('dashboard');
        Route::get('my-favourite', 'FavouriteController@index')->name('favourite.index');

        Route::get('preview/{id}', 'PreviewController@show')->name('preview.show');
        Route::get('profile', 'ProfileController@edit')->name('profile.edit');
        Route::post('profile', 'ProfileController@update')->name('profile.update');

        Route::get('change-password', 'ProfileController@changePassword')->name('change-password.edit');
        Route::post('change-password', 'ProfileController@updatePassword')->name('change-password.update');
        Route::post('review/{resourceId}', 'ReviewController@store')->name('review.store');

        Route::get('notifications', 'NotificationController@index')->name('notification.index');
        Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
        Route::get('notification/{id}/delete', 'NotificationController@destroy')->name('notification.destroy');
        Route::get('profile/view-user', 'ViewUserController@index')->name('view-user.index');

        Route::middleware('isAdminOrManagerOrEducator')
        ->group(function () {
            Route::get('profile/resource/{id}/new-version', 'ResourceController@clone')->name('resource.new-version');
            Route::resource('profile/resource', 'ResourceController')->middleware('canCRUDOnResource');

            // course
            Route::resource('profile/course', 'CourseController')->middleware('canCRUDOnCourse');
            Route::get('profile/take-course-user/{course_id}', 'CourseController@takeCourseUser')->name('take-course-user');                
            Route::middleware('canCRUDOnLecture')->group(function(){
                // lecture
                Route::get('profile/course-lecture/{course_id}/create', 'LectureController@create')->name('lecture.create')->middleware('canCRUDOnCourse');
                Route::post('profile/course-lecture/{course_id}/create', 'LectureController@store')->name('lecture.store')->middleware('canCRUDOnCourse');
                Route::get('profile/course-lecture/{lecture_id}/edit', 'LectureController@edit')->name('lecture.edit');
                Route::put('profile/course-lecture/{lecture_id}/edit', 'LectureController@update')->name('lecture.update');
                Route::delete('profile/course-lecture/{lecture_id}', 'LectureController@destroy')->name('lecture.destroy');

                });
            
            // assignment
            Route::get('profile/assignment/{id}', 'AssignmentController@show')->name('assignment.show');
            Route::get('profile/assignment/{id}/user-assignment', 'AssignmentController@userAssignment')->name('assignment.detail');
            // Route::post('profile/user-assignment/edit-comment', 'AssignmentController@updateComment')->name('assignment.comment');
            Route::post('profile/user-assignment/edit-comment', 'AssignmentReviewController@reviewAssignment')->name('ajax-assignment-review');
            Route::middleware('canCRUDOnAssignment')
            ->group(function(){
                Route::get('profile/course-assignment/{course_id}/create', 'AssignmentController@create')->name('assignment.create')->middleware('canCRUDOnCourse');
                Route::post('profile/course-assignment/{course_id}/create', 'AssignmentController@store')->name('assignment.store')->middleware('canCRUDOnCourse');
                Route::get('profile/course-assignment/{assignment_id}/edit', 'AssignmentController@edit')->name('assignment.edit');
                Route::put('profile/course-assignment/{assignment_id}/edit', 'AssignmentController@update')->name('assignment.update');
                Route::delete('profile/course-assignment/{assignment_id}', 'AssignmentController@destroy')->name('assignment.destroy');

            });

            // quiz
            Route::middleware('canCRUDOnQuiz')
            ->group(function(){
                Route::get('profile/course-quiz/{course_id}/create', 'QuizController@create')->name('quiz.create')->middleware('canCRUDOnCourse');
                Route::post('profile/course-quiz/{course_id}/create', 'QuizController@store')->name('quiz.store')->middleware('canCRUDOnCourse');
                Route::get('profile/course-quiz/{quiz_id}/edit', 'QuizController@edit')->name('quiz.edit');
                Route::put('profile/course-quiz/{quiz_id}/edit', 'QuizController@update')->name('quiz.update');
                Route::delete('profile/course-quiz/{quiz_id}', 'QuizController@destroy')->name('quiz.destroy');

            });

            // question
            Route::middleware('canCRUDOnQuestion')
            ->group(function(){
                Route::get('profile/course-question/{quiz_id}/create', 'QuestionController@create')->name('question.create');
                Route::post('profile/course-question/{quiz_id}/create', 'QuestionController@store')->name('question.store');
                Route::get('profile/course-question/{question_id}/edit', 'QuestionController@edit')->name('question.edit');
                Route::put('profile/course-question/{question_id}/edit', 'QuestionController@update')->name('question.update');
                Route::delete('profile/course-question/{question_id}', 'QuestionController@destroy')->name('question.destroy');
            });                 
            // Route::get('profile/user', 'UserController@index')
            //    ->name('user.index');
            Route::resource('profile/user', 'UserController')->only(['index', 'edit', 'update']);
            Route::get('profile/user/{id}/{action}', 'UserController@updateStatus')
                ->name('user.update-status');

            Route::get('profile/resource/create/{format}', 'ResourceController@create')
                ->name('resource.create-with-format');

            // Resource Approval Requests
            Route::get('profile/resource/{id}/submit', 'ApprovalRequestController@create')
                ->name('resource.submit-request');
            Route::post('profile/resource/{id}/submit', 'ApprovalRequestController@store')
                ->name('resource.save-submit-request');

            // Course Approval Requests
            Route::get('profile/course/{course_id}/submit', 'CourseApprovalRequestController@create')
                ->name('course.submit-request')->middleware('canCRUDOnCourse');
            Route::post('profile/course/{course_id}/submit', 'CourseApprovalRequestController@store')
                ->name('course.save-submit-request')->middleware('canCRUDOnCourse');

            Route::get('profile/resource/{id}/related', 'RelatedResourcesController@show')
                ->name('resource.related');
            Route::post('profile/resource/{id}/add-resource', 'RelatedResourcesController@store')
                ->name('resource.add-related');
            Route::get('profile/resource/{id}/remove-resource/{resource_id}', 'RelatedResourcesController@destroy')
                ->name('resource.remove-related');
        });

        // Only Admin and Manager users can access Approval Requests and take actions
        Route::middleware('isAdminOrManager')
            ->group(function () {
                Route::resource('profile/article', 'ArticleController');
                // Resource Approval
                Route::get('profile/request/{id}/{action}', 'ApprovalRequestController@updateStatus')
                    ->name('approval-request.update-status');
                Route::get('approval-request', 'ApprovalRequestController@index')->name('approval-request.index');
                // Course Approval
                Route::get('course-approval-request', 'CourseApprovalRequestController@index')->name('course-approval-request.index');
                Route::get('profile/course-request/{id}/{action}', 'CourseApprovalRequestController@updateStatus')
                    ->name('course-approval-request.update-status');
            });

        // Not only Admin and Manager users but also Teach Educator can view his/her Approval Request
        Route::get('approval-request/{id}', 'ApprovalRequestController@show')->name('approval-request.show');
        Route::get('course-approval-request/{id}', 'CourseApprovalRequestController@show')->name('course-approval-request.show');

        Route::get('profile/media/{id}/delete', 'MediaController@destroy')->name('media.destroy');
        Route::get('ajax-delete/{id}/media', 'MediaController@deleteMediaByResource')->name('ajax.media.destroy');
    });
