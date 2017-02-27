<?php

declare(strict_types=1);
Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'MainContentController@index');

    Route::resource('study/books', 'BookController');
    Route::put('study/books/{bookId}/buy', 'BookController@buy');

    Route::get('/register', 'RegistrationController@request');
    Route::post('/register', 'RegistrationController@submitRequest');

    Route::get('/admin', function () {
        return redirect('/admin/overview');
    });

    Route::get('/admin/overview', 'DashboardController@overview');
    Route::get('/admin/analytics', 'DashboardController@analytics');
    Route::get('/admin/export', 'DashboardController@export');

    // Proof of concept login & logout, currently not using a spcific user
    // so that we can show this potential functionality at the ALV
    Route::get('/login', function () {
        Auth::loginUsingId(1);

        return redirect('/');
    });
    Route::get('/logout', function () {
        try {
            Auth::logOut();
        } finally {
            return redirect('/');
        }
    });

    Route::group(['prefix' => 'admin'], function () {

        //committees
        Route::resource('committee', 'CommitteeController', ['except' => ['edit']]);

        //posts: NEWS / BLOG
        Route::resource('post', 'PostController');

        Route::resource('activity', 'ActivityController');

        //dit moet nog beter
        Route::post('committee/search-member', 'CommitteeController@searchMember');
        Route::post('committee/{committeeId}/member/{memberId}', 'CommitteeController@addMember');
        Route::delete('committee/{committeeId}/member/{memberId}', 'CommitteeController@removeMember');

        Route::get('member', 'MemberController@index');
        Route::post('member/add-member', 'MemberController@addMember');

        Route::get('registration-requests', 'Admin\RegistrationRequestsController@index');
        Route::get('registration-requests/{requestId}', 'Admin\RegistrationRequestsController@show');

        // Francken Vrij
        Route::get('francken-vrij', 'Admin\FranckenVrijController@index');
        Route::get('francken-vrij/{edition}', 'Admin\FranckenVrijController@edit');
        Route::put('francken-vrij/{edition}', 'Admin\FranckenVrijController@update');
        Route::delete('francken-vrij/{edition}', 'Admin\FranckenVrijController@destroy');
        Route::post('francken-vrij', 'Admin\FranckenVrijController@store');
    });

    Route::get('{page}', 'MainContentController@page')->where('page', '.+');
});
