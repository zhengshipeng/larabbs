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

Route::get('/', 'TopicsController@index')->name('root');

/** 登录，退出相关路由 **/
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

/** 注册路由 **/
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

/**  重置密码相关路由 **/
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

/** 密码认证相关路由 **/
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

/** email 认证相关路由 **/
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

/** 用户路由 **/
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

/** 话题路由 **/
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');
/** 话题编辑器上传图片 **/
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

/** 分类路由 **/
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

/** 话题回复路由 **/
Route::resource('replies', 'RepliesController', ['only' => ['store','destroy']]);

/** 通知路由 **/
Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

/** 无权访问后台路由 **/
Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');