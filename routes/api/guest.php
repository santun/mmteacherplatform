<?php

use Illuminate\Http\Request;

Route::namespace('API\Guest')->name('api.guest.')->group(function () {
    /* Usable List */
    Route::get('article-category', 'ListingController@getArticleCategories');
    Route::get('accessible-right', 'ListingController@getAccessibleRights');
    Route::get('report-type', 'ListingController@getReportTypes');
    Route::get('user-type', 'ListingController@getUserTypes');
    Route::get('notification-channel', 'ListingController@getNotificationChannel');
    Route::get('subjects', 'ListingController@getSubjects');
    Route::get('resource-format', 'ListingController@getResourceFormats');
    Route::get('region-state', 'ListingController@getRegionsAndStates');
    Route::get('colleges', 'ListingController@getCollege');
    Route::get('year-study-teaching', 'ListingController@getYearofTeaching');
    Route::get('resource/{resourceId}/reviews', 'ReviewController@index');

    Route::apiResource('page', 'PageController')->only(['index','show']);

    Route::apiResource('faq-category', 'FaqCategoryController')->only(['index','show']);
    Route::get('faq-category/{slug}/faqs', 'FaqCategoryController@getFaqs');

    Route::apiResource('faq', 'FaqController')->only(['index','show']);

    Route::get('/article', 'ArticleController@index')->name('api.article.index');
    Route::get('/article/{id}', 'ArticleController@show')->name('api.article.show');
    Route::get('/article/category/{slug}', 'ArticleCategoryController@show')->name('api.article.category');

    Route::apiResource('slide', 'SlideController')->only(['index','show']);

    Route::apiResource('advanced-search', 'AdvancedSearchController')->only(['index','show']);
    Route::apiResource('search', 'SearchController')->only(['index','show']);
    Route::apiResource('resource', 'ResourceController')->only(['index','show']);
    Route::get('resource/{id}/related', 'RelatedResourceController@show')->name('api.resource.related');
    Route::get('featured-resource', 'FeaturedResourceController@index')->name('api.resource.featured');

    Route::apiResource('subject', 'SubjectController')->only(['index','show']);
    Route::apiResource('license', 'LicenseController')->only(['index','show']);

    Route::get('subject/{slug}/resource', 'SubjectController@getResources');

    Route::post('contact', 'ContactController@store')->name('contact.store');
});

Route::namespace('API\Guest')
    ->name('api.user.')
    ->prefix('user')
    ->middleware('auth:api')
    ->group(function () {
        Route::apiResource('advanced-search', 'AdvancedSearchController')->only(['index','show']);
        Route::apiResource('search', 'SearchController')->only(['index','show']);
        Route::apiResource('resource', 'ResourceController')->only(['index','show']);
    });
