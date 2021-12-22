<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath',
    ],
], function () {

    Auth::routes();

    // Account Verification
    Route::get('verify/get_otp', [
        'as' => 'auth.verify.get_otp',
        'uses' => 'Auth\VerifyOTPController@getOTP',
    ]);

    Route::post('verify/post_otp', [
        'as' => 'auth.verify.post_otp',
        'uses' => 'Auth\VerifyOTPController@verifyOTP',
    ]);

    Route::get('request/otp', [
        'as' => 'auth.request.otp',
        'uses' => 'Auth\VerifyOTPController@requestOTP',
    ]);

    Route::post('resend/otp', [
        'as' => 'auth.resend.otp',
        'uses' => 'Auth\VerifyOTPController@resendOTP',
    ]);

    // Forgot Password
    Route::get('choose/password_reset_option', [
        'as' => 'auth.get.password_reset_option',
        'uses' => 'Auth\SendPasswordResetTokenController@chooseOption',
    ]);

    Route::post('choose/password_reset_option', [
        'as' => 'auth.post.password_reset_option',
        'uses' => 'Auth\SendPasswordResetTokenController@redirectOption',
    ]);

    Route::get('request/credientials', [
        'as' => 'auth.get.request_credientials',
        'uses' => 'Auth\SendPasswordResetTokenController@requestCredentials',
    ]);

    Route::post('reset-password/send_reset_token', [
        'as' => 'auth.reset-password.send_reset_token',
        'uses' => 'Auth\SendPasswordResetTokenController@sendPasswordResetToken',
    ]);

    Route::get('reset-password/get-token', [
        'as' => 'auth.reset-password.get-token',
        'uses' => 'Auth\SendPasswordResetTokenController@getToken',
    ]);

    Route::post('reset-password/verify-token', [
        'as' => 'auth.reset-password.verify-token',
        'uses' => 'Auth\SendPasswordResetTokenController@verifyToken',
    ]);

    Route::get('reset-password/{token}', [
        'as' => 'auth.get.reset-password',
        'uses' => 'Auth\SendPasswordResetTokenController@showPasswordResetForm',
    ]);

    Route::post('reset-password/{token}', [
        'as' => 'auth.post.reset-password',
        'uses' => 'Auth\SendPasswordResetTokenController@resetPassword',
    ]);

    require base_path() . '/routes/backend.php';
    require base_path() . '/routes/member.php';

    // Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/search', 'SearchController@index')->name('search.index');
    Route::get('/search/advanced', 'AdvancedSearchController@index')->name('search.advanced');

    Route::get('/elibrary', 'ElibraryController@browse')->name('elibrary.index');
    Route::get('/browse', 'ElibraryController@browse')->name('elibrary.browse');
    Route::get('/resources', 'ResourceController@index')->name('resource.index');

    Route::get('/resource/{resourceId}/favourite', 'Member\FavouriteController@store')
                ->name('resource.favourite')
                ->middleware(['auth']);
    Route::get('/resource/{resourceId}/unfavourite', 'Member\FavouriteController@destroy')
                ->name('resource.unfavourite')
                ->middleware(['auth']);

    Route::get('/resource/{media}/download', 'ResourceController@download')->name('resource.download');
    Route::get('/resource/{slug}', 'ResourceController@show')->name('resource.show');
    Route::get('/resource/preview/{id}', 'PreviewController@show')->name('resource.preview');

    Route::get('/subject/{slug}', 'SubjectController@show')->name('subject.show');
    Route::get('/resource_format/{format}', 'ResourceController@indexByFormat')
                ->name('resource_format.resource.index');

    Route::get('/media', 'ArticleController@index')->name('article.index');
    Route::get('/article/{slug}', 'ArticleController@show')->name('article.show');
    Route::get('/article/category/{slug}', 'ArticleCategoryController@show')->name('article.category');

    Route::get('/quiz/{id}', 'QuizController@answerQuiz')->name('quiz.show');
    Route::post('/quiz/check-answer', 'QuizController@checkAnswer')->name('quiz.check-answer');

    Route::group(['middleware' => 'auth', 'prefix' => 'e-learning/courses'], function () {
        Route::get('/my-course-view', 'CourseController@myCourses')
            ->name('courses.my-courses');
        Route::get('/take-course/{course}', 'CourseController@takeCourse')
            ->name('courses.take-course');
        Route::get('/learning/{course}/{lecture}', 'CourseController@learnCourse')
            ->name('courses.learn-course');
        Route::get('/assignment/{assignment}', 'AssignmentController@show')
            ->name('courses.view-assignment');
        Route::post('/assignment/{assignment}', 'AssignmentController@submitAssignment')
            ->name('courses.submit-assignment');
        Route::get('/assignment/view-feedback/{assignment_user}', 'AssignmentController@viewFeedback')
            ->name('courses.view-assignment-feedback');
        Route::get('/download-lecture/{lecture}', 'CourseController@downloadLecture')
            ->name('courses.download-lecture');
        Route::get('/download-course/{course}', 'CourseController@downloadCourse')
            ->name('courses.download-course');
    });
    Route::get('/e-learning', 'CourseController@index')->name('courses.index');
    Route::get('/e-learning/courses/{course}', 'CourseController@show')->name('courses.show');
    Route::get('e-learning/courses', 'CourseController@filterCourses')->name('filter-course');


    Route::get('/faq', 'FaqController@index')->name('faq.index');
    Route::get('/faq/{slug}', 'FaqController@show')->name('faq-category.show');

    Route::get('/@{username}', 'ProfileController@show')->name('profile.show');
    Route::get('/contact-us', 'ContactController@show')->name('contact-us.show');
    Route::post('/contact-us', 'ContactController@sendContact')->name('contact-us.post');

    Route::get('/{slug}', 'PageController@show')->name('page.show');
});

//Route::get('/', function () {
//    //return view('welcome');
//    return redirect()->to(config('app.locale'), 301);
//})->name('home');
