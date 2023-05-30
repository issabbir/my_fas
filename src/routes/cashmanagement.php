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
    Route::group(['name' => 'cm-report-generator', 'as' => 'cm-report-generator.'], function () {
        Route::get('/report-generators', 'Cm\ReportGeneratorController@index')->name('index');
        Route::get('/report-generator-params/{id}', 'Cm\ReportGeneratorController@reportParams')->name('report-params');

    });
    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        // Ajax request for Cash Management.

        Route::get('/cm-banks', 'Cm\AjaxController@cmBanks')->name('cm-banks');
        Route::get('/cm-bank/{bankCode}', 'Cm\AjaxController@cmBank')->name('cm-bank');

        Route::get('/cm-branches/{bankCode}', 'Cm\AjaxController@cmBranches')->name('cm-branches');
        Route::get('/cm-branch/{branchCode}', 'Cm\AjaxController@cmBranch')->name('cm-branch');

        Route::get('/cm-bank-districts', 'Cm\AjaxController@cmBankDistricts')->name('cm-bank-districts');
        Route::get('/cm-bank-district/{districtCode}', 'Cm\AjaxController@cmBankDistrict')->name('cm-bank-district');

        Route::get('/get-branches-on-bank', 'Cm\AjaxController@getBranchesOnBank')->name('get-branches-on-bank');

        Route::post('/gl-type-wise-coa-list', 'Cm\AjaxController@glTypeWiseCoaList')->name('gl-type-wise-coa-list');
        Route::get('/gl-type-acc-wise-coa', 'Cm\AjaxController@glTypeAccWiseCoa')->name('gl-type-acc-wise-coa');

        Route::get('/clearing_detail/{id}/{funcType}', 'Cm\AjaxController@getClearingDetail')->name('clearing_detail');

        Route::post('/interest-provision-list', 'Cm\AjaxController@interestProvisionList')->name('interest-provision-list');
        Route::post('/interest-provision-trans-view-list', 'Cm\AjaxController@interestProvisionTransViewList')->name('interest-provision-trans-view-list');

        Route::post('/fdr-search-datalist', 'Cm\AjaxController@fdrInvestmentList')->name('fdr-search-datalist');
        Route::post('/fdr-maturity-datalist', 'Cm\AjaxController@fdrMaturityList')->name('fdr-maturity-datalist');
        Route::post('/fdr-details', 'Cm\AjaxController@fdrInvestmentDetails')->name('fdr-details');
        Route::post('/fdr-maturity-details', 'Cm\AjaxController@fdrMaturityDetails')->name('fdr-maturity-details');
        Route::get('/get-fdr-register-period','Cm\AjaxController@getPostingPeriod')->name('get-fdr-register-period');

        Route::get('/bill-section-by-register/{sectionId}', 'Cm\AjaxController@sectionByRegisterList')->name('bill-section-by-register');
        Route::get('/gl-posting-periods/{nullable?}', 'Cm\AjaxController@reportGlPostingPeriods');
    });

    Route::group(['name' => 'bank-setup', 'as' => 'bank-setup.'], function () {
        Route::get('/bank-setup', 'Cm\BankSetUpController@index')->name('index');
        Route::post('/bank-setup', 'Cm\BankSetUpController@store')->name('store');
        Route::get('/bank-setup/{id}', 'Cm\BankSetUpController@edit')->name('edit');
        Route::put('/bank-setup/{id}', 'Cm\BankSetUpController@update')->name('update');
        Route::post('/bank-setup-datatable-list', 'Cm\BankSetUpController@dataTableList')->name('datatable-list');
        Route::get('/bank-setup-delete/{id}', 'Cm\BankSetUpController@delete')->name('delete');
    });

    Route::group(['name' => 'clearing-account-setup', 'as' => 'clearing-account-setup.'], function () {
        Route::get('/clearing-account-setup', 'Cm\ClearingAccountSetupController@index')->name('index');
        Route::post('/clearing-account-setup', 'Cm\ClearingAccountSetupController@store')->name('store');
        Route::get('/clearing-account-setup/{id}', 'Cm\ClearingAccountSetupController@edit')->name('edit');
        Route::put('/clearing-account-setup/{id}', 'Cm\ClearingAccountSetupController@update')->name('update');
        Route::post('/clearing-account-setup-datatable-list', 'Cm\ClearingAccountSetupController@dataTableList')->name('datatable-list');
        Route::get('/clearing-account-setup-delete/{id}', 'Cm\ClearingAccountSetupController@delete')->name('delete');
    });

    Route::group(['name' => 'cheque-book-setup', 'as' => 'cheque-book-setup.'], function () {
        Route::get('/cheque-book-setup', 'Cm\ChequeBookSetupController@index')->name('index');
        Route::post('/cheque-book-setup', 'Cm\ChequeBookSetupController@store')->name('store');
        Route::get('/cheque-book-setup/{id}', 'Cm\ChequeBookSetupController@edit')->name('edit');
        Route::put('/cheque-book-setup/{id}', 'Cm\ChequeBookSetupController@update')->name('update');
        Route::post('/cheque-book-setup-datatable-list', 'Cm\ChequeBookSetupController@dataTableList')->name('datatable-list');
        Route::post('/cheque-book-setup-leaf-list', 'Cm\ChequeBookSetupController@dataTableLeafList')->name('datatable-leaf-list');
        Route::get('/cheque-book-setup-delete/{id}', 'Cm\ChequeBookSetupController@delete')->name('delete');
    });

    Route::group(['name' => 'bank-branch-setup', 'as' => 'bank-branch-setup.'], function () {
        Route::get('/bank-branch-setup', 'Cm\BankBranchSetupController@index')->name('index');
        Route::post('/bank-branch-setup', 'Cm\BankBranchSetupController@store')->name('store');
        Route::get('/bank-branch-setup/{id}', 'Cm\BankBranchSetupController@edit')->name('edit');
        Route::put('/bank-branch-setup/{id}', 'Cm\BankBranchSetupController@update')->name('update');
        Route::post('/bank-branch-setup-datatable-list', 'Cm\BankBranchSetupController@dataTableList')->name('datatable-list');
        Route::get('/bank-branch-setup-delete/{id}', 'Cm\BankBranchSetupController@delete')->name('delete');
    });

    Route::group(['name' => 'clearing-reconciliation', 'as' => 'clearing-reconciliation.'], function () {
        Route::get('/clearing-reconciliation', 'Cm\ClearingReconciliation@index')->name('index');
        Route::post('/clearing-reconciliation', 'Cm\ClearingReconciliation@store')->name('store');
        Route::post('/clearing-reconciliation-datalist', 'Cm\ClearingReconciliation@dataList')->name('datalist');
    });

    Route::group(['name' => 'clearing-reconciliation-list', 'as' => 'clearing-reconciliation-list.'], function () {
        Route::get('/clearing-reconciliation-list', 'Cm\ClearingReconciliationList@index')->name('index');
        Route::post('/clearing-reconciliation-list-datalist', 'Cm\ClearingReconciliationList@dataList')->name('datalist');
        Route::put('/clearing-reconciliation-list', 'Cm\ClearingReconciliationList@update')->name('update');
    });

    Route::group(['name'=>'clearing-reconciliation-authorize','as'=>'clearing-reconciliation-authorize.'],function (){
        Route::get('/clearing-reconciliation-authorize','Cm\ClearingReconciliationAuthorizeController@index')->name('index');
        Route::any('/clearing-reconciliation-authorize-search', 'Cm\ClearingReconciliationAuthorizeController@searchClearingReconciliationAuthorize')->name('clearing-reconciliation-authorize-search');
        Route::post('/clearing-reconciliation-authorize', 'Cm\ClearingReconciliationAuthorizeController@approveReject')->name('approve-reject-store');
    });
    /*** FDR STARTS ***/
    Route::group(['name' => 'fdr-investment-setup', 'as' => 'fdr-investment-setup.'], function () {
        Route::get('/fdr-investment-setup', 'Cm\FdrInvestmentSetupController@index')->name('index');
        Route::post('/fdr-investment-setup', 'Cm\FdrInvestmentSetupController@store')->name('store');
        Route::get('/fdr-investment-setup/{id}', 'Cm\FdrInvestmentSetupController@edit')->name('edit');
        Route::put('/fdr-investment-setup/{id}', 'Cm\FdrInvestmentSetupController@update')->name('update');
        Route::post('/fdr-investment-setup-datatable-list', 'Cm\FdrInvestmentSetupController@dataTableList')->name('datatable-list');
        Route::get('/fdr-investment-setup-setup-delete/{id}', 'Cm\FdrInvestmentSetupController@delete')->name('delete');
    });

    Route::group(['name' => 'fdr-register', 'as' => 'fdr-register.', 'middleware' => ["checkInvestmentType"]], function () {
        Route::get('/fdr-register/{param?}', 'Cm\FdrRegisterController@index')->name('index');
        Route::post('/fdr-maturity-date', 'Cm\FdrRegisterController@fdtMaturityDate')->name('fdr-maturity-date');
        Route::post('/fdr-register', 'Cm\FdrRegisterController@store')->name('store');
        Route::put('/fdr-register/{id}', 'Cm\FdrRegisterController@update')->name('update');
        Route::post('/fdr-register-datatable-list', 'Cm\FdrRegisterController@dataTableList')->name('datatable-list');
    });

    Route::group(['name'=>'fdr-investment-register-authorize','as'=>'fdr-investment-register-authorize.', 'middleware' => ["checkInvestmentType"]],function (){
        Route::get('/fdr-investment-register-authorize/{filter?}','Cm\FdrInvRegAuthorizeController@index')->name('index');
        Route::any('/fdr-investment-register-authorize-search', 'Cm\FdrInvRegAuthorizeController@searchFdrInvRegAuthorize')->name('fdr-investment-register-authorize-search');
        Route::get('/fdr-investment-register-authorize/{invAuthLogId}/{filter?}', 'Cm\FdrInvRegAuthorizeController@approvalView')->name('approval-view');
        Route::post('/fdr-investment-register-authorize/{wkMapId}/{filter?}', 'Cm\FdrInvRegAuthorizeController@approveReject')->name('approve-reject-store');
    });

    Route::group(['name' => 'fdr-interest-provision-process', 'as' => 'fdr-interest-provision-process.' /*, 'middleware' => ["checkInvestmentType"]*/ ], function () {
        Route::get('/fdr-interest-provision-process', 'Cm\FdrIntProvisionProcessController@index')->name('index');
        Route::post('/fdr-interest-provision-process', 'Cm\FdrIntProvisionProcessController@store')->name('store');
        Route::get('/fdr-interest-provision-process/{id}', 'Cm\FdrIntProvisionProcessController@editView')->name('edit-view');
        Route::any('/fdr-interest-provision-datatable-list', 'Cm\FdrIntProvisionProcessController@dataTableList')->name('datatable-list');

    });

    Route::group(['name'=>'fdr-interest-prov-process-authorize','as'=>'fdr-interest-prov-process-authorize.', 'middleware' => ["checkInvestmentType"]],function (){
        Route::get('/fdr-interest-prov-process-authorize/{filter?}','Cm\FdrIntProvProcAuthorizeController@index')->name('index');
        Route::any('/fdr-interest-prov-process-authorize-search', 'Cm\FdrIntProvProcAuthorizeController@searchFdrIntProvProcAuthorize')->name('fdr-interest-prov-process-authorize-search');
        Route::get('/fdr-interest-prov-process-authorize/{provMstID}/{filter?}', 'Cm\FdrIntProvProcAuthorizeController@approvalView')->name('approval-view');
        Route::post('/fdr-interest-prov-process-authorize/{wkMapId}/{filter?}', 'Cm\FdrIntProvProcAuthorizeController@approveReject')->name('approve-reject-store');
    });

        /*** FDR Opening starts **/
    Route::group(['name'=>'fdr-opening','as'=>'fdr-opening.', 'middleware' => ["checkInvestmentType"]], function(){
        Route::get('/fdr-opening/{id?}','Cm\FdrOpeningController@index')->name('index');
        Route::post('/fdr-opening','Cm\FdrOpeningController@store')->name('store');
        Route::post('/fdr-opening-search-datalist','Cm\FdrOpeningController@dataList')->name('fdr-opening-search-datalist');
        Route::post('/fdr-opening-preview','Cm\FdrOpeningController@preview')->name('preview');
    });

        /*** FDR Opening Authorize **/
    Route::group(['name'=>'fdr-opening-authorize','as'=>'fdr-opening-authorize.', 'middleware' => ["checkInvestmentType"] ], function() {
        Route::get('/fdr-opening-authorize/{filter?}','Cm\FdrOpeningAuthorizeController@index')->name('index');
        Route::get('/fdr-opening-authorize/{id}/{filter}/{wkmId}','Cm\FdrOpeningAuthorizeController@view')->name('view');
        Route::post('/fdr-opening-authorize/{filter?}','Cm\FdrOpeningAuthorizeController@perform')->name('perform');
        Route::post('/fdr-opening-authorize-list','Cm\FdrOpeningAuthorizeController@dataList')->name('data-list');
    });

    /*** FDR Maturity starts **/
    Route::group( ['name'=>'fdr-maturity','as'=>'fdr-maturity.'/*, 'middleware' => ["checkInvestmentType"]*/ ], function() {
        Route::get('/fdr-maturity','Cm\FdrMaturityController@index')->name('index');
        Route::post('/fdr-maturity','Cm\FdrMaturityController@store')->name('store');;
        Route::get('/fdr-maturity/{id}','Cm\FdrMaturityController@view')->name('view');;
        Route::post('/fdr-maturity-search-datalist','Cm\FdrMaturityController@dataList')->name('fdr-maturity-search-datalist');
        Route::post('/fdr-maturity-preview','Cm\FdrMaturityController@preview')->name('preview');

        Route::post('/fdr-maturity/{id}','Cm\FdrMaturityController@update')->name('update');
    });

    Route::group(['name'=>'fdr-maturity-authorize','as'=>'fdr-maturity-authorize.' /*, 'middleware' => ["checkInvestmentType"]*/ ],function () {
        Route::get('/fdr-maturity-authorize/{filter?}','Cm\FdrMaturityAuthorizeController@index')->name('index');
        Route::any('/fdr-maturity-authorize-search', 'Cm\FdrMaturityAuthorizeController@searchFdrIntProvProcAuthorize')->name('fdr-maturity-authorize-search');
        Route::get('/fdr-maturity-authorize/{matTransId}/{filter?}', 'Cm\FdrMaturityAuthorizeController@approvalView')->name('approval-view');
        Route::post('/fdr-maturity-authorize/{wkMapId}/{filter?}', 'Cm\FdrMaturityAuthorizeController@approveReject')->name('approve-reject-store');
    });

    /*** FDR ENDS ***/

    //If route method not allowed
    Route::fallback(function () {
        abort(404);
    });

});
