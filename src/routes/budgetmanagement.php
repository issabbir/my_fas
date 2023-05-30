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
    Route::group(['name' => 'budget-mgt-report-generator', 'as' => 'budget-mgt-report-generator.'], function () {
        Route::get('/report-generators', 'BudgetManagement\ReportGeneratorController@index')->name('index');
        Route::get('/report-generator-params/{id}', 'BudgetManagement\ReportGeneratorController@reportParams')->name('report-params');
    });

    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        Route::get('/dept-period-on-calender','BudgetManagement\AjaxController@getDeptPeriod')->name('dept-period-on-calender');
        Route::get('/initial-budget-details','BudgetManagement\AjaxController@getInitialBudgetDetail')->name('initial-budget-details');
        Route::post('/review-approval-budget-details-list', 'BudgetManagement\AjaxController@reviewApprovalBudgetDetailsList')->name('review-approval-budget-details-list');
        Route::post('/budget-head-code-tree','BudgetManagement\AjaxController@budgetHeadTreeOnType')->name('budget-head-code-tree');
        Route::post('/budget-head-code-search-list','BudgetManagement\AjaxController@budgetHeadDataList')->name('budget-head-code-search-list');
        Route::post('/budget-head-edit-code-search-list','BudgetManagement\AjaxController@budgetHeadsearchDataList')->name('budget-head-edit-code-search-list');
        Route::post('/coa-acc-tree','BudgetManagement\AjaxController@coaAccTree')->name('coa-acc-tree');
        Route::get('/coa-info-details/{accountId}', 'BudgetManagement\AjaxController@coaInfoDetails')->name('coa-info-details');

        Route::get('/get-categories/{id}/{default?}','BudgetManagement\AjaxController@getCategoriesForBudget')->name('get-categories');
        Route::get('/get-sub-categories/{id}/{default?}','BudgetManagement\AjaxController@getSubCategoriesForCategory')->name('get-sub-categories');
    });

    Route::group(['name' => 'preparation','as' => 'preparation.'], function (){
        Route::get('/preparation','BudgetManagement\PreparationController@index')->name('index');
        Route::post('/preparation','BudgetManagement\PreparationController@store')->name('store');
        Route::get('/preparation/{id}/{mode?}','BudgetManagement\PreparationController@edit')->name('edit');
        Route::post('/budget-datalist','BudgetManagement\PreparationController@datalist')->name('budget-datalist');
    });

    Route::group(['name'=>'review-approval','as'=>'review-approval.'], function (){
        Route::get('/review-approval','BudgetManagement\ReviewApprovalController@index')->name('index');
        Route::post('/review-approval-datatable-list', 'BudgetManagement\ReviewApprovalController@dataTableList')->name('datatable-list');
        Route::get('/review-approval-view/{id}','BudgetManagement\ReviewApprovalController@approvalView')->name('approval-view');
        Route::post('/review-approval-store', 'BudgetManagement\ReviewApprovalController@approvalStore')->name('approval-store');
    });

    Route::group(['name' => 'budget-mgt-download','as' => 'budget-mgt-download.'], function (){
        Route::get('/download-budget-mgt-attachment/{id}','BudgetManagement\DownloaderController@downloadBudgetMgtDocs')->name('download-budget-mgt-attachment');
    });

    Route::group(['name' => 'budget-block-amount','as' => 'budget-block-amount.'], function (){
        Route::get('/block-amount','BudgetManagement\BudgetBlockController@index')->name('block-amount');
        Route::post('/block-amount','BudgetManagement\BudgetBlockController@store')->name('store');
        Route::get('/block-amount-history/{blockingId}','BudgetManagement\BudgetBlockController@blockHistory')->name('history');
        Route::post('/block-amount-update','BudgetManagement\BudgetBlockController@update')->name('update');
        Route::get('/block-amount-list','BudgetManagement\BudgetBlockController@dataList')->name('data-list');
    });

    Route::group(['name' => 'finalization','as' => 'finalization.'], function (){
        Route::get('/finalization','BudgetManagement\FinalizationController@index')->name('index');
        Route::post('/finalization','BudgetManagement\FinalizationController@process')->name('store');
        Route::post('/finalization-datalist','BudgetManagement\FinalizationController@datalist')->name('budget-datalist');
    });

    /** Budget Head*/
    Route::group(['name' => 'budget-head', 'as' => 'budget-head.'], function () {
        Route::get('/budget-head', 'BudgetManagement\BudgetAccController@index')->name('budget-head-index');
        Route::get('/budget-head-setup', 'BudgetManagement\BudgetAccController@headSetup')->name('budget-head-setup-index');
        Route::post('/budget-head-setup', 'BudgetManagement\BudgetAccController@store')->name('budget-head-store');
        Route::get('/budget-head-setup/{id}', 'BudgetManagement\BudgetAccController@edit')->name('budget-head-edit');
        Route::put('/budget-head-setup/{id}', 'BudgetManagement\BudgetAccController@update')->name('budget-head-update');
        Route::get('/budget-head-setup-view/{id}', 'BudgetManagement\BudgetAccController@view')->name('budget-head-view');
        Route::post('/budget-coa-acc-type-wise-list', 'Gl\CoaController@accTypeWiseCoa')->name('coa-acc-type-wise-list');
        //Route::post('/coa-budget-head-wise-line-list', 'BudgetManagement\CoaController@budgetHeadWiseLine')->name('coa-budget-head-wise-line-list');
        Route::post('/budget-coa-budget-head-list', 'BudgetManagement\CoaController@budgetHeadWiseList')->name('coa-budget-head-list');
        Route::any('/budget-coa-acc-name-code-search-list', 'BudgetManagement\CoaController@searchAccNamesCodes')->name('coa-acc-name-code-search-list');
    });



    Route::group(['name' => 'head-edit','as' => 'head-edit.'], function (){
        Route::get('/head-index','BudgetManagement\BudgetAccController@headEditIndex')->name('headEditIndex');
        Route::get('/budget-head-edit/{id}', 'BudgetManagement\BudgetAccController@headEdit')->name('headEdit');
        Route::get('/budget-head-view/{id}', 'BudgetManagement\BudgetAccController@headView')->name('headView');
//        Route::post('/budget-head-update/{id}', 'BudgetManagement\BudgetAccController@headUpdate')->name('headUpdate');
//        Route::post('/finalization','BudgetManagement\FinalizationController@process')->name('store');
//        Route::post('/finalization-datalist','BudgetManagement\FinalizationController@datalist')->name('budget-datalist');
    });

    //If route method not allowed
    Route::fallback(function () {
        abort(404);
    });

});
