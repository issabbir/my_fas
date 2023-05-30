<?php
/**
 *Created by PhpStorm
 *Created at ৫/৯/২১ ৪:৪৫ PM
 */

Route::group(['middleware' => ['auth']], function () {

    //Report Route
    Route::group(['name' => 'ap-report-generator', 'as' => 'ap-report-generator.'], function () {
        Route::get('/report-generators', 'Ap\ReportGeneratorController@index')->name('index');
        Route::get('/report-generator-params/{id}', 'Ap\ReportGeneratorController@reportParams')->name('report-params');
        Route::get('/gl-accounts', 'Ap\AjaxController@reportGlAccounts');
        Route::get('/gl-fiscal-years', 'Ap\AjaxController@reportGlFiscalYears');
        Route::get('/gl-posting-periods/{nullable?}', 'Ap\AjaxController@reportGlPostingPeriods');
        Route::get('/ap-vendors/{subsidiaryId}', 'Ap\AjaxController@reportApVendors');
    });

    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        Route::post('/contra-sub-ledgers', 'Ap\AjaxController@contraLedgers')->name('contra-sub-ledgers');
        Route::get('/get-branches-on-bank', 'Ap\AjaxController@getBranchesOnBank')->name('get-branches-on-bank');
        Route::post('/vendor-search-datalist', 'Ap\AjaxController@vendorList')->name('vendor-search-datalist');
        Route::get('/vendor-with-outstanding-balance', 'Ap\AjaxController@getVendorWithOutstandingBalance')->name('vendor-with-outstanding-balance');
        Route::get('/vendor-details', 'Ap\AjaxController@getVendorDetails')->name('vendor-details');
        Route::post('/add-vendor-details', 'Ap\AjaxController@getAddVendorDetails')->name('add-vendor-details');
        Route::get('/bank-account-details-for-invoice-entry', 'Ap\AjaxController@getBankAccountDetailsOnGlType')->name('bank-account-details-for-invoice-entry');
        Route::post('/invoice-acc-datalist', 'Ap\AjaxController@coaListOnSearch')->name('invoice-acc-datalist');
        Route::get('/get-po-detail', 'Ap\AjaxController@getPoDetail')->name('get-po-detail');
        Route::get('/get-invoice-types-on-subsidiary', 'Ap\AjaxController@getInvoiceTypesOnSubsidiary')->name('get-invoice-types-on-subsidiary');
        Route::get('/get-bill-pay-wise-on-subsidiary', 'Ap\AjaxController@getBillPayWiseOnSubsidiary')->name('get-bill-pay-wise-on-subsidiary');
        Route::get('/get-payment-favoring-info', 'Ap\AjaxController@getPaymentFavoringInfo')->name('get-payment-favoring-info');

        Route::post('/gl-type-wise-coa-list', 'Ap\AjaxController@glTypeWiseCoaList')->name('gl-type-wise-coa-list');
        Route::get('/gl-type-acc-wise-coa', 'Ap\AjaxController@glTypeAccWiseCoa')->name('gl-type-acc-wise-coa');
        Route::get('/gl-acc-wise-coa', 'Ap\AjaxController@glAccWiseCoa')->name('gl-acc-wise-coa');
        Route::get('/vendor-wise-vat-tax-info', 'Ap\AjaxController@vendorWiseVatTaxInfo')->name('vendor-wise-vat-tax-info');

        /*Route::get('/cm-banks', 'Ap\AjaxController@cmBanks')->name('cm-banks');
        Route::get('/cm-bank/{bankCode}', 'Ap\AjaxController@cmBank')->name('cm-bank');

        Route::get('/cm-bank-districts', 'Ap\AjaxController@cmBankDistricts')->name('cm-bank-districts');
        Route::get('/cm-bank-district/{districtCode}', 'Ap\AjaxController@cmBankDistrict')->name('cm-bank-district');*/

        Route::get('/bill-section-by-register/{sectionId}', 'Ap\AjaxController@sectionByRegisterList')->name('bill-section-by-register');
        Route::post('/invoice-reference-list', 'Ap\AjaxController@invoiceReferenceList')->name('invoice-reference-list');
        Route::post('/invoice-reference-cash-cheque-list', 'Ap\AjaxController@invoiceReferenceCashChequeList')->name('invoice-reference-cash-cheque-list');
        Route::post('/invoice-reference-tax-pay-list', 'Ap\AjaxController@invoiceReferenceTaxPayList')->name('invoice-reference-tax-pay-list');

        //Route::get('/clearing_detail/{id}/{funcType}', 'Ap\AjaxController@getClearingDetail')->name('clearing_detail');
        Route::post('/po-search-datalist', 'Ap\AjaxController@poList')->name('po-search-datalist');
        Route::get('/vendor-category-on-vendor-type', 'Ap\AjaxController@vendorCategories')->name('vendor-category-on-vendor-type');

        Route::get('/a-budget-booking-detail', 'Ap\AjaxController@budgetBookDetailInfo')->name('a-budget-detail');
        Route::post('/budget-head-datalist', 'Ap\AjaxController@budgetHeadDatalist')->name('budget-head-datalist');
        Route::post('/budget-booking-datalist', 'Ap\AjaxController@budgetBookingDatalist')->name('budget-booking-datalist');
        Route::get('/get-bill-register-detail/{id}','Ap\AjaxController@getRegisterDetail');
    });

    Route::group(['name' => 'invoice-bill-parameter', 'as' => 'invoice-bill-parameter.'], function () {
        Route::get('/invoice-bill-parameter', 'Ap\InvoiceBillParameterController@index')->name('index');
        Route::post('/invoice-bill-parameter', 'Ap\InvoiceBillParameterController@insert')->name('insert');
        Route::get('/invoice-bill-parameter/{id}', 'Ap\InvoiceBillParameterController@edit')->name('edit');
        Route::put('/invoice-bill-parameter/{id}', 'Ap\InvoiceBillParameterController@update')->name('update');
        Route::get('/invoice-bill-parameter-delete/{id}', 'Ap\InvoiceBillParameterController@delete')->name('delete');
        Route::post('/invoice-bill-parameter-datalist', 'Ap\InvoiceBillParameterController@dataList')->name('datalist');
    });

    Route::group(['name' => 'vendor-account-balance-inquiry', 'as' => 'vendor-account-balance-inquiry.'], function () {
        Route::get('/vendor-account-balance-inquiry', 'Ap\VendorAccountBalanceInquiryController@index')->name('index');
    });

    Route::group(['name' => 'vendor-profile', 'as' => 'vendor-profile.'], function () {
        Route::get('/vendor-profile', 'Ap\VendorProfileController@index')->name('index');
        Route::post('/vendor-profile', 'Ap\VendorProfileController@insert')->name('insert');
        Route::get('/vendor-profile/{id}/{view?}', 'Ap\VendorProfileController@edit')->name('edit');
        Route::put('/vendor-profile/{id}', 'Ap\VendorProfileController@update')->name('update');
        Route::get('/delete-vendor-profile/{id}', 'Ap\VendorProfileController@delete')->name('delete');
    });

    Route::group(['name' => 'vendor-profile-authorize', 'as' => 'vendor-profile-authorize.'], function () {
        Route::get('/vendor-profile-authorize', 'Ap\VendorProfileAuthController@index')->name('index');
        Route::get('/vendor-profile-authorize/{id}', 'Ap\VendorProfileAuthController@vendorAuthorize')->name('authorize');
        Route::get('/vendor-profile-perform-authorize', 'Ap\VendorProfileAuthController@performAuthorize')->name('perform-authorize');
        Route::post('/vendor-profile-authorize-datalist', 'Ap\VendorProfileAuthController@dataList')->name('vendor-profile-authorize-datalist');
    });

    Route::group(['name' => 'vendor-search', 'as' => 'vendor-search.'], function () {
        Route::get('/vendor-search', 'Ap\VendorSearchController@index')->name('index');
        Route::post('/vendor-search', 'Ap\VendorSearchController@insert')->name('insert');
        Route::get('/vendor-search/{id}', 'Ap\VendorSearchController@edit')->name('edit');
        Route::put('/vendor-search', 'Ap\VendorSearchController@update')->name('update');
        Route::get('/delete-vendor-search/{id}', 'Ap\VendorSearchController@delete')->name('delete');
        Route::post('/vendor-search-datalist', 'Ap\VendorSearchController@dataList')->name('datalist');
    });

    /*Route::group(['name' => 'bank-setup', 'as' => 'bank-setup.'], function () {
        Route::get('/bank-setup', 'Ap\BankSetUpController@index')->name('index');
        Route::post('/bank-setup', 'Ap\BankSetUpController@store')->name('store');
        Route::get('/bank-setup/{id}', 'Ap\BankSetUpController@edit')->name('edit');
        Route::put('/bank-setup/{id}', 'Ap\BankSetUpController@update')->name('update');
        Route::post('/bank-setup-datatable-list', 'Ap\BankSetUpController@dataTableList')->name('datatable-list');
        Route::get('/bank-setup-delete/{id}', 'Ap\BankSetUpController@delete')->name('delete');
    });

    Route::group(['name' => 'clearing-account-setup', 'as' => 'clearing-account-setup.'], function () {
        Route::get('/clearing-account-setup', 'Ap\ClearingAccountSetupController@index')->name('index');
        Route::post('/clearing-account-setup', 'Ap\ClearingAccountSetupController@store')->name('store');
        Route::get('/clearing-account-setup/{id}', 'Ap\ClearingAccountSetupController@edit')->name('edit');
        Route::put('/clearing-account-setup/{id}', 'Ap\ClearingAccountSetupController@update')->name('update');
        Route::post('/clearing-account-setup-datatable-list', 'Ap\ClearingAccountSetupController@dataTableList')->name('datatable-list');
        Route::get('/clearing-account-setup-delete/{id}', 'Ap\ClearingAccountSetupController@delete')->name('delete');
    });

    Route::group(['name' => 'cheque-book-setup', 'as' => 'cheque-book-setup.'], function () {
        Route::get('/cheque-book-setup', 'Ap\ChequeBookSetupController@index')->name('index');
        Route::post('/cheque-book-setup', 'Ap\ChequeBookSetupController@store')->name('store');
        Route::get('/cheque-book-setup/{id}', 'Ap\ChequeBookSetupController@edit')->name('edit');
        Route::put('/cheque-book-setup/{id}', 'Ap\ChequeBookSetupController@update')->name('update');
        Route::post('/cheque-book-setup-datatable-list', 'Ap\ChequeBookSetupController@dataTableList')->name('datatable-list');
        Route::post('/cheque-book-setup-leaf-list', 'Ap\ChequeBookSetupController@dataTableLeafList')->name('datatable-leaf-list');
        Route::get('/cheque-book-setup-delete/{id}', 'Ap\ChequeBookSetupController@delete')->name('delete');
    });

    Route::group(['name' => 'bank-branch-setup', 'as' => 'bank-branch-setup.'], function () {
        Route::get('/bank-branch-setup', 'Ap\BankBranchSetupController@index')->name('index');
        Route::post('/bank-branch-setup', 'Ap\BankBranchSetupController@store')->name('store');
        Route::get('/bank-branch-setup/{id}', 'Ap\BankBranchSetupController@edit')->name('edit');
        Route::put('/bank-branch-setup/{id}', 'Ap\BankBranchSetupController@update')->name('update');
        Route::post('/bank-branch-setup-datatable-list', 'Ap\BankBranchSetupController@dataTableList')->name('datatable-list');
        Route::get('/bank-branch-setup-delete/{id}', 'Ap\BankBranchSetupController@delete')->name('delete');
    });*/

    Route::group(['name' => 'invoice-bill-entry', 'as' => 'invoice-bill-entry.'], function () {
        Route::get('/invoice-bill-entry', 'Ap\InvoiceBillEntryController@index')->name('index');
        Route::post('/invoice-bill-entry', 'Ap\InvoiceBillEntryController@insert')->name('insert');
        Route::post('/invoice-bill-preview', 'Ap\InvoiceBillEntryController@preview')->name('invoice-bill-preview');
    });



    Route::group(['name' => 'invoice-salary-bill-entry', 'as' => 'invoice-salary-bill-entry.'], function () {
        Route::get('/invoice-salary-bill-entry', 'Ap\InvoiceSalaryBillEntryController@index')->name('index');
        Route::get('/invoice-salary-pay-allowance/{id}', 'Ap\InvoiceSalaryBillEntryController@payAllowance')->name('payAllowance');
        Route::get('/invoice-salary-pay-deduction/{id}', 'Ap\InvoiceSalaryBillEntryController@deduction')->name('deduction');
        Route::post('/invoice-salary-bill-entry', 'Ap\InvoiceSalaryBillEntryController@insert')->name('insert');
        Route::post('/invoice-salary-bill-preview', 'Ap\InvoiceSalaryBillEntryController@preview')->name('invoice-salary-bill-preview');
    });



    Route::group(['name' => 'invoice-bill-listing', 'as' => 'invoice-bill-listing.'], function () {
        Route::get('/invoice-bill-listing/{filter?}', 'Ap\InvoiceBillListingController@index')->name('index');
        Route::get('/invoice-bill-listing/{id}', 'Ap\InvoiceBillListingController@edit')->name('edit');
        Route::put('/invoice-bill-listing', 'Ap\InvoiceBillListingController@update')->name('hold-un-hold');
        Route::post('/invoice-bill-listing-datalist', 'Ap\InvoiceBillListingController@dataList')->name('datalist');
        Route::get('/invoice-bill-listing-view/{id}/{filter?}', 'Ap\InvoiceBillListingController@view')->name('view');
        Route::post('/invoice-bill-listing-update', 'Ap\InvoiceBillListingController@updateInvoiceEntry')->name('update');
        Route::get('/invoice-bill-listing-download/{id}', 'Ap\InvoiceBillListingController@download')->name('download');
    });

    /*Route::group(['name' => 'clearing-reconciliation', 'as' => 'clearing-reconciliation.'], function () {
        Route::get('/clearing-reconciliation', 'Ap\ClearingReconciliation@index')->name('index');
        Route::post('/clearing-reconciliation', 'Ap\ClearingReconciliation@store')->name('store');
        Route::post('/clearing-reconciliation-datalist', 'Ap\ClearingReconciliation@dataList')->name('datalist');
    });

    Route::group(['name' => 'clearing-reconciliation-list', 'as' => 'clearing-reconciliation-list.'], function () {
        Route::get('/clearing-reconciliation-list', 'Ap\ClearingReconciliationList@index')->name('index');
        Route::post('/clearing-reconciliation-list-datalist', 'Ap\ClearingReconciliationList@dataList')->name('datalist');
        Route::put('/clearing-reconciliation-list', 'Ap\ClearingReconciliationList@update')->name('update');
    });*/

    Route::group(['name' => 'invoice-bill-payment', 'as' => 'invoice-bill-payment.'], function () {
        Route::get('/invoice-bill-payment', 'Ap\InvoiceBillPaymentController@index')->name('index');
        Route::post('/invoice-bill-payment', 'Ap\InvoiceBillPaymentController@store')->name('store');
        Route::get('/invoice-bill-payment/{id}/{filter?}', 'Ap\InvoiceBillPaymentController@view')->name('view');
        Route::post('/invoice-bill-payment-queue-list', 'Ap\InvoiceBillPaymentController@paymentQueueLists')->name('payment-queue-lists');
        Route::post('/invoice-bill-payment-tax-queue-list', 'Ap\InvoiceBillPaymentController@taxPaymentQueueLists')->name('payment-tax-queue-lists');
        Route::get('/invoice-bill-payment-attachment/download/{id}', 'Ap\DownloaderController@invoiceBillPayAttachment')->name('attachment-download');
        Route::post('/invoice-bill-payment-update', 'Ap\InvoiceBillPaymentController@updateInvoiceEntry')->name('update');
        Route::post('/invoice-bill-payment-draft','Ap\InvoiceBillPaymentController@makeDraft')->name('invoice-bill-payment-draft');
    });

    Route::group(['name' => 'invoice-bill-payment-list', 'as' => 'invoice-bill-payment-list.'], function () {
        Route::get('/invoice-bill-payment-list/{filter?}', 'Ap\InvoiceBillPayListController@index')->name('index');
        Route::any('/invoice-bill-payment-search-list', 'Ap\InvoiceBillPayListController@searchInvoicePayment')->name('invoice-bill-payment-search-list');
    });

    Route::group(['name'=>'invoice-bill-authorize','as'=>'invoice-bill-authorize.'],function (){
        Route::get('/invoice-bill-authorize/{filter?}','Ap\InvoiceBillAuthorizeController@index')->name('index');
        Route::any('/invoice-bill-authorize-search', 'Ap\InvoiceBillAuthorizeController@searchInvoiceBillAuthorize')->name('invoice-bill-authorize-search');
        Route::get('/invoice-bill-authorize/{invId}/{filter?}', 'Ap\InvoiceBillAuthorizeController@approvalView')->name('approval-view');
        Route::post('/invoice-bill-authorize/{wkMapId}/{filter?}', 'Ap\InvoiceBillAuthorizeController@approveRejectCancel')->name('approve-reject-store');
        Route::post('/ap-invoice-bill-authorize-by-list', 'Ap\InvoiceBillAuthorizeController@approveByList')->name('ap-invoice-bill-authorize-by-list');
    });

    Route::group(['name'=>'invoice-bill-payment-authorize','as'=>'invoice-bill-payment-authorize.'],function (){
        Route::get('/invoice-bill-payment-authorize/{filter?}','Ap\InvoiceBillPayAuthorizeController@index')->name('index');
        Route::any('/invoice-bill-payment-authorize-search', 'Ap\InvoiceBillPayAuthorizeController@searchInvoicePaymentAuthorize')->name('invoice-bill-payment-authorize-search');
        Route::get('/invoice-bill-payment-authorize/{payId}/{filter?}', 'Ap\InvoiceBillPayAuthorizeController@approvalView')->name('approval-view');
        Route::post('/invoice-bill-payment-authorize/{wkMapId}/{filter?}', 'Ap\InvoiceBillPayAuthorizeController@approveRejectCancel')->name('approve-reject-store');
        Route::post('/ap-invoice-bill-payment-authorize-by-list', 'Ap\InvoiceBillPayAuthorizeController@approveByList')->name('ap-invoice-bill-payment-authorize-by-list');
    });

    /*Route::group(['name'=>'clearing-reconciliation-authorize','as'=>'clearing-reconciliation-authorize.'],function (){
        Route::get('/clearing-reconciliation-authorize','Ap\ClearingReconciliationAuthorizeController@index')->name('index');
        Route::any('/clearing-reconciliation-authorize-search', 'Ap\ClearingReconciliationAuthorizeController@searchOutwardClearingReconciliationAuthorize')->name('clearing-reconciliation-authorize-search');
        Route::post('/clearing-reconciliation-authorize', 'Ap\ClearingReconciliationAuthorizeController@approveReject')->name('approve-reject-store');
    });*/

//If route method not allowed
    Route::fallback(function () {
        abort(404);
    });

});


