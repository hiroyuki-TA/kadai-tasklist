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
/**
 * HTTPメソッド
 * POST    作成 (create)
 * GET     取得 (read)
 * PUT     更新 (update)
 * DELETE  削除 (delete)
 */
 
 /**
  * Controller名 @ アクション名 
  */
//topページをTasksControllerのindexに設定
Route::get('/', 'TasksController@index');
Route::resource('tasks', 'TasksController');

// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// 認証
//->name('login')『名前つきルート』
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

Route::group(['middleware' => ['auth']], function () {
//Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
Route::resource('tasks', 'TasksController', ['only' => ['store', 'destroy','show','index','create']]);
    
});
