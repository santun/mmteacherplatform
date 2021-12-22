<?php
// Member Routes
Route::namespace('API\Member')
    ->middleware(['auth:api',
        'isApproved',
        'isVerified',
        // 'isBlocked'
        // 'isAdmin', 'isBlocked'
    ])
    ->prefix('member')
    ->name('api.member.')

    ->group(function () {
        Route::get('dashboard', 'DashboardController')->name('dashboard');

        Route::get('favourite', 'FavouriteController@index')->name('favourite.index');
        Route::get('add-to-favourite/{resourceId}', 'FavouriteController@store')->name('favourite.store');
        Route::get('remove-from-favourite/{resourceId}', 'FavouriteController@destroy')->name('favourite.delete');

        Route::resource('resource', 'ResourceController');

        Route::get('resource/{id}/new-version', 'ResourceController@clone')->name('resource.new-version');

        Route::get('notification', 'NotificationController@index')->name('notification.index');
        Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
        Route::delete('notification/{id}', 'NotificationController@destroy')->name('notification.destroy');
        Route::get('notification/{id}/{action}', 'NotificationController@updateStatus')
            ->name('notification.update-status');

        // Approval Requests
        Route::post('submit-for-approval/{id}', 'ApprovalRequestController@store')
            ->name('resource.save-submit-request');

        // Only Admin and Manager users can access Approval Requests and take actions
        Route::middleware('isAdminOrManager')
            ->group(function () {
                Route::resource('article', 'ArticleController');

                Route::get('approval-request/{id}/{action}', 'ApprovalRequestController@updateStatus')
                    ->name('approval-request.update-status');

                Route::get('approval-request', 'ApprovalRequestController@index')
                    ->name('approval-request.index');

                Route::get('user/{id}/{action}', 'UserController@updateStatus')
                    ->name('user.update-status');
                Route::post('user/{id}', 'UserController@update')
                    ->name('user.update');
            });

        // Not only Admin and Manager users but also Teach Educator can view his/her Approval Request
        Route::get('approval-request/{id}', 'ApprovalRequestController@show')
        ->name('approval-request.show');

        Route::get('user', 'UserController@index')
            ->name('user.index');

        Route::post('profile', 'ProfileController@update')->name('profile.update');

        Route::post('change-password', 'ProfileController@updatePassword')
            ->name('change-password.update');

        // Route::get('media/{id}/delete', 'MediaController@destroy')->name('media.destroy');
        Route::post('resource/{resourceId}/review', 'ReviewController@store')
            ->name('review.store');
        Route::post('resource/{resourceId}/report', 'LinkReportController@store')
            ->name('link-report.store');

        Route::get('resource/{id}/related', 'RelatedResourcesController@show')
            ->name('resource.related');
        Route::post('resource/{id}/add-resource', 'RelatedResourcesController@store')
            ->name('resource.add-related');
        Route::get('resource/{resource_id}/remove-resource/{related_resource_id}', 'RelatedResourcesController@destroy')
            ->name('resource.remove-related');
    });
