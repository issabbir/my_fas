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
    Route::group(['name' => 'ar-report-generator', 'as' => 'ar-report-generator.'], function () {
        Route::get('/report-generators', 'Ar\ReportGeneratorController@index')->name('index');
        Route::get('/report-generator-params/{id}', 'Ar\ReportGeneratorController@reportParams')->name('report-params');
        Route::get('/gl-accounts', 'Ar\AjaxController@reportGlAccounts');
        Route::get('/gl-fiscal-years', 'Ar\AjaxController@reportGlFiscalYears');
        Route::get('/gl-posting-periods', 'Ar\AjaxController@reportGlPostingPeriods');
        Route::get('/ar-customers/{subsidiaryId}', 'Ar\AjaxController@reportArCustomers');
    });

    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        // Ajax request for Cash Management.
/*        Route::post('/contra-sub-ledgers', 'Ar\AjaxController@contraLedgers')->name('contra-sub-ledgers');*/

        Route::get('/customer-details', 'Ar\AjaxController@getCustomerDetails')->name('customer-details');
        Route::post('/customer-search-datalist', 'Ar\AjaxController@customerList')->name('customer-search-datalist');
        Route::get('/customer-with-outstanding-balance', 'Ar\AjaxController@getCustomerWithOutstandingBalance')->name('customer-with-outstanding-balance');
        Route::get('/get-invoice-types-on-subsidiary', 'Ar\AjaxController@getTransactionTypesOnSubsidiary')->name('get-invoice-types-on-subsidiary');

        Route::post('/po-search-datalist', 'Ar\AjaxController@poList')->name('po-search-datalist');
        Route::post('/invoice-acc-datalist', 'Ar\AjaxController@coaListOnSearch')->name('invoice-acc-datalist');

        Route::get('/account-details-for-invoice-entry', 'Ar\AjaxController@getAccountDetailsOnGlType')->name('account-details-for-invoice-entry');

        Route::get('/bill-section-by-register/{sectionId}', 'Ar\AjaxController@sectionByRegisterList')->name('bill-section-by-register');
        Route::post('/invoice-reference-list', 'Ar\AjaxController@invoiceReferenceList')->name('invoice-reference-list');

        Route::get('/get-shipping-agent-detail','Ar\AjaxController@shippingAgentDetail')->name('get-shipping-agent-detail');
        Route::get('/get-bill-register-detail/{id}','Ar\AjaxController@getRegisterDetail');

    });

    Route::group(['name' => 'ar-invoice-bill-parameter', 'as' => 'ar-invoice-bill-parameter.'], function () {
        Route::get('/ar-invoice-bill-parameter', 'Ar\InvoiceBillParameterController@index')->name('index');
        Route::post('/ar-invoice-bill-parameter', 'Ar\InvoiceBillParameterController@insert')->name('insert');
        Route::get('/ar-invoice-bill-parameter/{id}', 'Ar\InvoiceBillParameterController@edit')->name('edit');
        Route::put('/ar-invoice-bill-parameter/{id}', 'Ar\InvoiceBillParameterController@update')->name('update');
        Route::get('/ar-invoice-bill-parameter-delete/{id}', 'Ar\InvoiceBillParameterController@delete')->name('delete');
        Route::post('/ar-invoice-bill-parameter-datalist', 'Ar\InvoiceBillParameterController@dataList')->name('datalist');
    });

    Route::group(['name' => 'customer-account-balance-inquiry', 'as' => 'customer-account-balance-inquiry.'], function () {
        Route::get('/customer-account-balance-inquiry', 'Ar\CustomerAccountBalanceInquiryController@index')->name('index');
    });

    Route::group(['name' => 'customer-profile', 'as' => 'customer-profile.'], function () {
        Route::get('/customer-profile', 'Ar\CustomerProfileController@index')->name('index');
        Route::post('/customer-profile', 'Ar\CustomerProfileController@insert')->name('insert');
        Route::get('/customer-profile/{id}/{view?}/{auth?}', 'Ar\CustomerProfileController@edit')->name('edit');
        Route::put('/customer-profile/{id}', 'Ar\CustomerProfileController@update')->name('update');
        Route::get('/delete-customer-profile/{id}', 'Ar\CustomerProfileController@delete')->name('delete');
    });

    Route::group(['name' => 'customer-profile-authorize', 'as' => 'customer-profile-authorize.'], function () {
        Route::get('/customer-profile-authorize', 'Ar\CustomerProfileAuthController@index')->name('index');
        Route::get('/customer-profile-authorize/{id}', 'Ar\CustomerProfileAuthController@customerAuthorize')->name('authorize');
        Route::get('/customer-profile-perform-authorize', 'Ar\CustomerProfileAuthController@performAuthorize')->name('perform-authorize');
        /*Route::post('/customer-profile-authorize', 'Ar\CustomerProfileAuthController@insert')->name('insert');
        Route::get('/customer-profile-authorize/{id}/{view?}', 'Ar\CustomerProfileAuthController@edit')->name('edit');
        Route::put('/customer-profile-authorize/{id}', 'Ar\CustomerProfileAuthController@update')->name('update');*/
        Route::post('/customer-profile-authorize-datalist', 'Ar\CustomerProfileAuthController@dataList')->name('customer-profile-authorize-datalist');
    });

    Route::group(['name' => 'customer-search', 'as' => 'customer-search.'], function () {
        Route::get('/customer-search', 'Ar\CustomerSearchController@index')->name('index');
        Route::post('/customer-search', 'Ar\CustomerSearchController@insert')->name('insert');
        Route::get('/customer-search/{id}', 'Ar\CustomerSearchController@edit')->name('edit');
        Route::put('/customer-search', 'Ar\CustomerSearchController@update')->name('update');
        Route::get('/delete-customer-search/{id}', 'Ar\CustomerSearchController@delete')->name('delete');
        Route::post('/customer-search-datalist', 'Ar\CustomerSearchController@dataList')->name('datalist');
    });

    Route::group(['name' => 'ar-invoice-bill-entry', 'as' => 'ar-invoice-bill-entry.'], function () {
        Route::get('/ar-invoice-bill-entry', 'Ar\InvoiceBillEntryController@index')->name('index');
        Route::post('/ar-invoice-bill-entry', 'Ar\InvoiceBillEntryController@insert')->name('insert');
    });

    Route::group(['name' => 'ar-invoice-bill-listing', 'as' => 'ar-invoice-bill-listing.'], function () {
        Route::get('/ar-invoice-bill-listing/{filter?}', 'Ar\InvoiceBillListingController@index')->name('index');
        Route::get('/ar-invoice-bill-listing/{id}', 'Ar\InvoiceBillListingController@edit')->name('edit');
        Route::put('/ar-invoice-bill-listing', 'Ar\InvoiceBillListingController@update')->name('hold-un-hold');
        Route::post('/ar-invoice-bill-listing-datalist', 'Ar\InvoiceBillListingController@dataList')->name('datalist');
        Route::get('/ar-invoice-bill-listing-view/{id}/{filter?}', 'Ar\InvoiceBillListingController@view')->name('view');
        Route::get('/ar-invoice-bill-listing-download/{id}', 'Ar\InvoiceBillListingController@download')->name('download');
        Route::post('/ar-invoice-bill-listing-update', 'Ar\InvoiceBillListingController@updateInvoiceEntry')->name('update');
    });

    Route::group(['name' => 'invoice-bill-receipt', 'as' => 'invoice-bill-receipt.'], function () {
        Route::get('/invoice-bill-receipt', 'Ar\InvoiceBillReceiptController@index')->name('index');
        Route::post('/invoice-bill-receipt', 'Ar\InvoiceBillReceiptController@store')->name('store');
        Route::get('/invoice-bill-receipt/{id}/{filter?}', 'Ar\InvoiceBillReceiptController@view')->name('view');
        Route::get('/invoice-bill-receipt-attachment/download/{id}', 'Ar\DownloaderController@invoiceBillReceiptAttachment')->name('attachment-download');
        Route::post('/invoice-bill-receipt-update', 'Ar\InvoiceBillReceiptController@updateInvoiceEntry')->name('update');

    });

    Route::group(['name' => 'invoice-bill-receipt-list', 'as' => 'invoice-bill-receipt-list.'], function () {
        Route::get('/invoice-bill-receipt-list/{filter?}', 'Ar\InvoiceBillReceiptListController@index')->name('index');
        Route::any('/invoice-bill-receipt-search-list', 'Ar\InvoiceBillReceiptListController@searchInvoiceReceipt')->name('invoice-bill-receipt-search-list');
    });

    Route::group(['name'=>'ar-invoice-bill-authorize','as'=>'ar-invoice-bill-authorize.'],function (){
        Route::get('/ar-invoice-bill-authorize/{filter?}','Ar\InvoiceBillAuthorizeController@index')->name('index');
        Route::any('/ar-invoice-bill-authorize-search', 'Ar\InvoiceBillAuthorizeController@searchInvoiceBillAuthorize')->name('invoice-bill-authorize-search');
        Route::get('/ar-invoice-bill-authorize/{invId}/{filter?}', 'Ar\InvoiceBillAuthorizeController@approvalView')->name('approval-view');
        Route::post('/ar-invoice-bill-authorize/{wkMapId}/{filter?}', 'Ar\InvoiceBillAuthorizeController@approveRejectCancel')->name('approve-reject-store');
        Route::post('/ar-invoice-bill-authorize-by-list', 'Ar\InvoiceBillAuthorizeController@approveByList')->name('ar-invoice-bill-authorize-by-list');
    });

    Route::group(['name'=>'invoice-bill-receipt-authorize','as'=>'invoice-bill-receipt-authorize.'],function (){
        Route::get('/invoice-bill-receipt-authorize/{filter?}','Ar\InvoiceBillReceiptAuthorizeController@index')->name('index');
        Route::any('/invoice-bill-receipt-authorize-search', 'Ar\InvoiceBillReceiptAuthorizeController@searchInvoiceReceiptAuthorize')->name('invoice-bill-receipt-authorize-search');
        Route::get('/invoice-bill-receipt-authorize/{payId}/{filter?}', 'Ar\InvoiceBillReceiptAuthorizeController@approvalView')->name('approval-view');
        Route::post('/invoice-bill-receipt-authorize/{wkMapId}/{filter?}', 'Ar\InvoiceBillReceiptAuthorizeController@approveRejectCancel')->name('approve-reject-store');
        Route::post('/invoice-bill-receipt-authorize-by-list', 'Ar\InvoiceBillReceiptAuthorizeController@approveByList')->name('invoice-bill-receipt-authorize-by-list');
    });

    //If route method not allowed
    Route::fallback(function () {
        abort(404);
    });

});
