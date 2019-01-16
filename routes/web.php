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
//登录路由
Route::get('/login', 'Admin\LoginController@index');
Route::get('/login/loginOut', 'Admin\LoginController@loginOut');
Route::match(['get','post'],'/login/getVerify/{t}', 'Admin\LoginController@getVerify')->where('t','[0-9]+');
Route::match(['get','post'],'/login/doLogin', 'Admin\LoginController@doLogin');
Route::match(['get','post'],'/login/resetpwd', 'Admin\LoginController@resetpwd');
Route::match(['get','post'],'/login/resetemail/{_token}', 'Admin\LoginController@resetemail');

Route::group(['middleware' =>[ 'check']], function () {
    Route::get('/', 'Admin\IndexController@index')->name('index');
//首页路由
        Route::group(['middleware' =>[ 'permison']], function () {
        Route::get('/index/index', 'Admin\IndexController@info');
        Route::match(['get', 'post'], '/index/clear_cache', 'Admin\IndexController@clear_cache');
        Route::post('/index/change', 'Admin\IndexController@change');

//菜单路由
        Route::get('menu/index', 'Admin\MenuController@index');
        Route::post('/menu/create', 'Admin\MenuController@create');
        Route::post('/menu/softEdit', 'Admin\MenuController@softEdit');
        Route::match(['get', 'post'], '/menu/edit_rule/{id}', 'Admin\MenuController@edit_rule')->where('id', '[0-9]+');
        Route::match(['get', 'post'], '/menu/del', 'Admin\MenuController@del');
        Route::post('/menu/edit_rule', 'Admin\MenuController@edit_rule');
        Route::match(['get', 'post'], '/menu/status', 'Admin\MenuController@status');
//角色路由
        Route::get('/role/index', 'Admin\RoleController@index');
        Route::match(['get', 'post'], '/role/getRole', 'Admin\RoleController@getRole');
        Route::match(['get', 'post'], '/role/roleadd', 'Admin\RoleController@add');
        Route::match(['get', 'post'], '/role/edit/{id}', 'Admin\RoleController@edit')->where('id', '[0-9]+');
        Route::match(['get', 'post'], '/role/edit', 'Admin\RoleController@edit');
        Route::post('/role/del/{id}', 'Admin\RoleController@del')->where('id', '[0-9]+');
        Route::match(['get', 'post'], '/role/del', 'Admin\RoleController@del');
        Route::match(['get', 'post'], '/role/status', 'Admin\RoleController@status');
        Route::match(['get', 'post'], '/role/giveAccess', 'Admin\RoleController@giveAccess');

//用户路由
        Route::get('/user/index', 'Admin\UserController@index');
        Route::match(['get', 'post'], '/user/getUser', 'Admin\UserController@getUser');
        Route::match(['get', 'post'], '/user/useradd', 'Admin\UserController@useradd');
        Route::match(['get', 'post'], '/user/del', 'Admin\UserController@del');
        Route::match(['get', 'post'], '/user/user_state', 'Admin\UserController@user_state');
        Route::match(['get', 'post'], '/user/userEdit', 'Admin\UserController@userEdit');
        //日志管理
        Route::match(['get', 'post'], 'admin/log/index', 'Admin\LogController@index');
        Route::match(['get', 'post'], 'admin/log/operate_log', 'Admin\LogController@operate_log');
        Route::match(['get', 'post'], 'admin/log/del', 'Admin\LogController@del');
        Route::match(['get', 'post'], 'admin/log/Alldel', 'Admin\LogController@Alldel');

        //数据库
        Route::match(['get', 'post'], 'admin/data/index', 'Admin\DataController@index');
        Route::match(['get', 'post'], 'admin/data/optimize', 'Admin\DataController@optimize');
        Route::match(['get', 'post'], 'admin/data/repair', 'Admin\DataController@repair');
        Route::match(['get', 'post'], 'admin/data/export', 'Admin\DataController@export');
        Route::match(['get', 'post'], 'admin/data/back', 'Admin\DataController@back');

        //项目管理
        Route::match(['get', 'post'], 'admin/project/index', 'Admin\ProjectController@index');
        Route::match(['get', 'post'], 'admin/project/add', 'Admin\ProjectController@add');
        Route::match(['get', 'post'], 'admin/project/giveCate', 'Admin\ProjectController@giveCate');
        Route::match(['get', 'post'], 'admin/project/Info', 'Admin\ProjectController@Info');
        Route::match(['get', 'post'], 'admin/project/del', 'Admin\ProjectController@del');
        Route::match(['get', 'post'], 'admin/project/softEdit', 'Admin\ProjectController@softEdit');

    });
});
