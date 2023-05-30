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

Route::group(['middleware' => ['auth']], function () {
    //Report Route
    Route::group(['name' => 'budget-mon-report-generator', 'as' => 'budget-mon-report-generator.'], function () {
        Route::get('/report-generators', 'BudgetMonitoring\ReportGeneratorController@index')->name('index');
        Route::get('/report-generator-params/{id}', 'BudgetMonitoring\ReportGeneratorController@reportParams')->name('report-params');
    });

    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        Route::get('/a-budget-detail', 'BudgetMonitoring\AjaxController@budgetDetailInfo')->name('a-budget-detail');
        Route::post('/budget-head-datalist', 'BudgetMonitoring\AjaxController@budgetHeadDatalist')->name('budget-head-datalist');
        Route::get('/budget-head-list', 'BudgetMonitoring\AjaxController@budgetListForReport')->name('budget-head-list');
        Route::get('/dept-period-on-calender','BudgetMonitoring\AjaxController@getDeptPeriod')->name('dept-period-on-calender');
        Route::get('/bill-section-by-register/{sectionId}', 'BudgetMonitoring\AjaxController@sectionByRegisterList')->name('bill-section-by-register');
        Route::get('/vendor-details', 'BudgetMonitoring\AjaxController@getVendorDetails')->name('vendor-details');
        Route::post('/vendor-search-datalist', 'BudgetMonitoring\AjaxController@vendorList')->name('vendor-search-datalist');
        Route::get('/get-bill-register-detail/{id}','BudgetMonitoring\AjaxController@getRegisterDetail');
    });

    Route::group(['name' => 'concurrence-transaction', 'as' => 'concurrence-transaction.'], function () {
        Route::get('/concurrence-transaction/{filter?}', 'BudgetMonitoring\ConcurrenceTransactionController@index')->name('index');
        Route::post('/concurrence-transaction','BudgetMonitoring\ConcurrenceTransactionController@store')->name('store');
        Route::get('/concurrence-transaction/{booking_id}/{mode}/{filter?}', 'BudgetMonitoring\ConcurrenceTransactionController@edit')->name('edit');
        Route::put('/concurrence-transaction','BudgetMonitoring\ConcurrenceTransactionController@update')->name('update');
        //Route::get('/finalization/{id}/{mode?}','BudgetFinalization\FinalizationController@edit')->name('edit');*/
        //Route::post('/budget-datalist','BudgetMonitoring\ConcurrenceTransactionController@datalist')->name('budget-datalist');
    });

    Route::group(['name' => 'concurrence-transaction-list', 'as' => 'concurrence-transaction-list.'], function () {
        Route::get('/concurrence-transaction-list/{filter?}', 'BudgetMonitoring\ConcurrenceTranListController@index')->name('index');
        Route::any('/concurrence-transaction-search-list', 'BudgetMonitoring\ConcurrenceTranListController@searchConcurrenceTransaction')->name('concurrence-transaction-search-list');
        Route::get('/concurrence-transaction-view/{id}/{filter?}', 'BudgetMonitoring\ConcurrenceTranListController@view')->name('view');
    });

    Route::group(['name' => 'concurrence-transaction-authorization', 'as' => 'concurrence-transaction-authorization.'], function () {
        Route::get('/concurrence-transaction-authorization/{filter?}', 'BudgetMonitoring\ConcurrenceTranAuthorizeController@index')->name('index');
        Route::any('/concurrence-transaction-authorization-search-list', 'BudgetMonitoring\ConcurrenceTranAuthorizeController@searchConcurrenceTransactionAuth')->name('concurrence-transaction-authorization-search-list');
        Route::get('/concurrence-transaction-authorization-view', 'BudgetMonitoring\ConcurrenceTranAuthorizeController@approvalView')->name('approval-view');
        /*Route::get('/concurrence-transaction-authorization/{booking_id}/{mode}', 'BudgetMonitoring\ConcurrenceTranAuthorizeController@edit')->name('edit');
        Route::post('/concurrence-transaction-authorization','BudgetMonitoring\ConcurrenceTranAuthorizeController@update')->name('update');*/
        Route::post('/concurrence-transaction-authorization/{wkMapId}/{filter?}', 'BudgetMonitoring\ConcurrenceTranAuthorizeController@approveReject')->name('approve-reject-store');
        Route::post('/concurrence-transaction-authorize/', 'BudgetMonitoring\ConcurrenceTranAuthorizeController@approveBudget')->name('approve');
    });

    Route::group(['name' => 'budget-mon-download','as' => 'budget-mon-download.'], function (){
        Route::get('/download-budget-mon-attachment/{id}','BudgetMonitoring\DownloaderController@downloadBudgetMonDocs')->name('download-budget-mon-attachment');
    });

    //If route method not allowed
    Route::fallback(function () {
        abort(404);
    });

});
