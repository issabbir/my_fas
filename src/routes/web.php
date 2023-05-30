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

Route::get('/', 'UserController@index')->name('login');

Route::post('/authorization/login', 'Auth\LoginController@authorization')->name('authorization.login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/user/change-password', function () {
        return view('resetPassword');
    })->name('change-password');

    Route::post('/user/change-password', 'Auth\ResetPasswordController@resetPassword')->name('user.reset-password');
    Route::post('/report/render/{title}', 'Report\OraclePublisherController@render')->name('report');
    Route::get('/report/render/{title?}', 'Report\OraclePublisherController@render')->name('report-get');
    Route::post('/authorization/logout', 'Auth\LoginController@logout')->name('logout');

    //Report Route
    /*Route::group(['name' => 'report-generator', 'as' => 'ap-report-generator.'], function () {
        Route::get('/report-generators', 'Ap\ReportGeneratorController@index')->name('index');
        Route::get('/report-generator-params/{id}', 'Ap\ReportGeneratorController@reportParams')->name('report-params');
    });*/

    // For News
    Route::get('/get-top-news', 'NewsController@getNews')->name('get-top-news');
    Route::get('/news-download/{id}', 'NewsController@downloadAttachment')->name('news-download');

    //For Ajax
    Route::group(['prefix'=>'ajax','as'=>'ajax'],function (){
        Route::get('/employees', 'AjaxController@employees')->name('employees');
        Route::get('/employee/{empId}', 'AjaxController@employee')->name('employee');
        Route::post('/coa-acc-datalist', 'AjaxController@coaAccDatatable')->name('coa-acc-datalist');
        Route::post('/account-details', 'AjaxController@coaDetail')->name('account-details');

        Route::get('/functions-on-module', 'AjaxController@functionTypesOfAmodule')->name('functions-on-module');
        Route::get('/billSection-on-functions', 'AjaxController@billSectionsOnAFunction')->name('billSection-on-functions');

        Route::get('/old-periods-from-to', 'AjaxController@oldPeriodFromTo')->name('old-periods-from-to');

    });
    Route::get('/page-not-allowed/{message}', 'HomeController@pageNotAllowed')->name('page-not-allowed');

    //For Workflow
//    Route::group(['prefix'=>'workflow','as'=>'workflow.'],function (){
//        Route::get('/master','Workflow\MasterController@index')->name('master-index');
//        Route::get('/master-datatable','Workflow\MasterController@masterDatatable')->name('master-datatable');
//    });

    //If route method not allowed
    Route::fallback(function () {
        abort(404);
    });
});

