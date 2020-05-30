<?php

Route::redirect('/', '/login');

Route::redirect('/home', '/admin/results');

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::get('/', 'HomeController@index')->name('home');

    Route::post('/save', 'HomeController@saveConfigFile');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');

    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');

    Route::resource('roles', 'RolesController');

    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::resource('users', 'UsersController');

    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');

    Route::resource('products', 'ProductsController');

    Route::delete('results/destroy', 'ResultsController@massDestroy')->name('results.massDestroy');

    Route::post('results/edit','ResultsController@edit');

    Route::post('results/create/download','ResultsController@download');

    Route::resource('results', 'ResultsController');

    Route::post('tests/create','TestController@store');

    Route::post('tests/delete', 'TestController@destroy');

    Route::get('tests', 'TestController@index');

    Route::resource('tests', 'TestController');
});

Route::resource('tests', 'TestController');
