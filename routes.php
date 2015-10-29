<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

use App\Model\Blog;

Route::get('/', function () {
    return view('welcome');
});
Route::get('index', 'AuthController@home');
Route::get('home', 'AuthController@home');
Route::get('aboutus', 'AuthController@aboutus');
Route::get('contactus', 'AuthController@contactus');
Route::get('blogs/{id}','BlogController@view');
Route::get('login', 'AuthController@Entry');
Route::get('show', 'AuthController@showCategory');
Route::get('logout', 'AuthController@logout');
Route::get('term','AuthController@term');
Route::get('getImage/{imageName}', 'BlogController@getImage');
Route::get('forgot','AuthController@forgot');
Route::group(['middleware' => 'user_Auth'], function() {
    Route::get('deleteUser/{id}', 'AuthController@deleteUser');
    Route::get('manage/blogs', 'BlogRouteController@manage');

    Route::get('delete/{id}', 'BlogController@delete');
    Route::resource('user', 'UserRouteController');
    Route::get('showUser', 'UserRouteController@showUsers');
    Route::get('createblog', 'BlogController@createblog');
    Route::post('createblog', 'BlogController@store');
    Route::get('blogs/{id}/delete', 'BlogController@delete');
    Route::get('user/{id}/delete', 'AuthController@delete');
});

Route::group(['middleware' => 'checkUser'], function() {
        Route::get('blogs/edit/{id}', 'BlogController@edit');
});

Route::group(['middleware' => 'Login'], function() {
    Route::get('user/{id}','UserRouteController@show');
    Route::get('blogs/manage','BlogController@manage');
    Route::get('login', 'AuthController@Entry');
    Route::get('manage', 'AuthController@check');
    Route::post('manage', 'AuthController@check');
    Route::get('signup', 'AuthController@signup');
    Route::post('signup', 'AuthController@store');
});
Route::group(['middleware' => 'auth'], function() {
   Route::resource('blogs', 'BlogRouteController');
   Route::put('blogs/edit/{id}','BlogController@update');
});
Route::get('success','AuthController@success');
Route::get('blog','BlogRouteController@index');
Route::get('blog/search','BlogController@search');
Route::group(['middleware' => 'Admin'], function() {
    Route::get('admin', 'AdminAuthController@admin');
    Route::get('admin/makeAdmin/{id}', 'AdminAuthController@makeAdmin');
    Route::get('admin/makeUser/{id}', 'AdminAuthController@makeUser');
    Route::get('admin/userLogin/{id}', 'AdminAuthController@userLogin');
    Route::get('admin/blogs/delete/{id}','AdminAuthController@deleteBlog');
    Route::post('admin/team/add','AdminAuthController@addMember');
    Route::post('admin/category', 'AdminAuthController@category');
    Route::post('admin/category/{id}','AdminAuthcontroller@editCategory');
    Route::get('admin/category','AdminAuthController@listCategory');
    Route::get('admin/category/{id}', 'AdminAuthController@editCategory');
    Route::post('saveCategory', 'AdminAuthController@saveCategory');
    Route::get('admin/deleteUser/{id}', 'AdminAuthController@deleteUser');
    Route::get('admin/sort/{id}', 'AdminAuthController@sort');
    Route::get('admin/blogs/{id}', 'AdminAuthController@sortBlog');
    Route::get('admin/category/delete/{id}','BlogController@deleteCategory');
    Route::get('admin/users', 'AdminAuthController@users');
    Route::get('admin/blogs', 'AdminAuthController@manage');
    Route::get('admin/team','AdminAuthController@team');
    Route::get('admin/teams','AdminAuthController@listTeam');
    Route::get('admin/team/delete/{id}','AdminAuthController@deleteTeamMember');
    Route::get('admin/team/edit/{id}','AdminAuthController@editTeamMember');
    Route::put('admin/team/edit','AdminAuthController@updateMember');
    Route::get('user/{id}/edit','AuthController@edit');

});
 Route::get('mail','AuthController@mail');
 Route::post('sendMail','AuthController@sendMail');