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
    Route::group(['name' => 'gl-report-generator', 'as' => 'gl-report-generator.'], function () {
        Route::get('/report-generators', 'Gl\ReportGeneratorController@index')->name('index');
        Route::get('/report-generator-params/{id}', 'Gl\ReportGeneratorController@reportParams')->name('report-params');
        Route::get('/gl-accounts', 'Gl\AjaxController@reportGlAccounts');
        Route::get('/gl-fiscal-years', 'Gl\AjaxController@reportGlFiscalYears');
        Route::get('/gl-posting-periods', 'Gl\AjaxController@reportGlPostingPeriods');
    });

    Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
        // Ajax request for General Ledger.
        Route::get('/gl-accounts', 'Gl\AjaxController@glAccountsList')->name('gl-accounts');

        Route::get('/bill-section-by-register/{sectionId}', 'Gl\AjaxController@sectionByRegisterList')->name('bill-section-by-register');
        Route::get('/bill-register-detail/{registerId}', 'Gl\AjaxController@getRegisterDetail')->name('bill-register-details');
        Route::get('/fun-type-by-debit-bank-acc/{funTypeId}', 'Gl\AjaxController@funTypeByDebitBankAccList')->name('fun-type-by-debit-bank-acc');
        Route::get('/fun-type-by-credit-bank-acc/{funTypeId}', 'Gl\AjaxController@funTypeByCreditBankAccList')->name('fun-type-by-credit-bank-acc');

        Route::get('/bank-account-details/{accountId}', 'Gl\AjaxController@bankAccountDetails')->name('bank-account-details');
        Route::post('/bank-account-details', 'Gl\AjaxController@bankAccountDetailsByFuncId')->name('bank-account-details');
        Route::post('/get-account-details', 'Gl\AjaxController@getAccountInfo')->name('get-account-details');
        Route::post('/get-party-account-details', 'Gl\AjaxController@getPartyAccountInfo')->name('get-party-account-details');

        Route::get('/gl-transaction-mst-details', 'Gl\AjaxController@glTransactionMstDetails')->name('gl-transaction-mst-details');

 		Route::post('/cash-account-details', 'Gl\AjaxController@cashAccountDetails')->name('cash-account-details');
        Route::post('/revenue-account-details', 'Gl\AjaxController@revenueAccountDetails')->name('revenue-account-details');

        //Route::get('/budget-head-line-details/{budgetHeadLineId}', 'Common\AjaxController@budgetHeadLineDetails')->name('budget-head-line-details');
        Route::get('/budget-head-details/{budgetHeadId}', 'Common\AjaxController@budgetHeadDetails')->name('budget-head-details');
        Route::post('/acc-datalist', 'Gl\AjaxController@coaAccDatatable')->name('acc-datalist');
        Route::post('/coa-details', 'Gl\AjaxController@coaDetails')->name('coa-details');

        /**
         * COA ADD (problem: parent code must be non-postable). REF# email
         * Use fas_gl_config.get_gl_coa_info instead of fas_gl_trans.get_gl_account_info
         * for parent account code detail.
         * Logic added:04-04-2022
         * **/
        Route::get('/coa-info-details/{accountId}/{accType}', 'Gl\AjaxController@coaInfoDetails')->name('coa-info-details');

        Route::get('/get-current-bank-account','Gl\AjaxController@getCurrentBankAccount')->name('get-current-bank-account');
        Route::get('/get-current-posting-period','Gl\AjaxController@getCurrentPostingPeriod')->name('get-current-posting-period');
        Route::get('/get-year-end-posting-period','Gl\AjaxController@getYearEndPostingPeriod')->name('get-year-end-posting-period');
    });

    Route::group(['name' => 'calendar', 'as' => 'calendar.'], function () {
        Route::get('/calendar','Gl\CalendarController@index')->name('index');
        Route::get('/calendar-setup','Gl\CalendarController@setup')->name('setup');
        Route::post('/calendar-store','Gl\CalendarController@store')->name('store');

        Route::get('/calendar-default-setup','Gl\CalendarController@defaultSetup')->name('default-setup');
        Route::post('/calendar-default-store','Gl\CalendarController@defaultStore')->name('default-store');

        Route::get('/calendar-detail/{id}','Gl\CalendarController@detailView')->name('detail-view');
        Route::get('/calendar-status-list-on-detail/{detailId}','Gl\CalendarController@statusList')->name('status-list-on-detail');
        Route::post('/calendar-detail', 'Gl\CalendarController@detailStore')->name('detail-store');
        Route::post('/calendar-datatable-list','Gl\CalendarController@calendarList')->name('calendar-datatable-list');

    });

    Route::group(['name' => 'coa', 'as' => 'coa.'], function () {
        Route::get('/coa', 'Gl\CoaController@index')->name('index');
        Route::get('/coa-setup', 'Gl\CoaController@coaSetup')->name('coa-setup-index');
        Route::post('/coa-setup', 'Gl\CoaController@store')->name('coa-setup-store');
        Route::get('/coa-setup/{id}', 'Gl\CoaController@edit')->name('coa-setup-edit');
        Route::put('/coa-setup/{id}', 'Gl\CoaController@update')->name('coa-setup-update');
        Route::get('/coa-setup-view/{id}', 'Gl\CoaController@view')->name('coa-setup-view');

        Route::post('/coa-acc-type-wise-list', 'Gl\CoaController@accTypeWiseCoa')->name('coa-acc-type-wise-list');
        //Route::post('/coa-budget-head-wise-line-list', 'Gl\CoaController@budgetHeadWiseLine')->name('coa-budget-head-wise-line-list');
        Route::post('/coa-budget-head-list', 'Gl\CoaController@budgetHeadWiseList')->name('coa-budget-head-list');
        Route::any('/coa-acc-name-code-search-list', 'Gl\CoaController@searchAccNamesCodes')->name('coa-acc-name-code-search-list');
    });

    Route::group(['name'=>'cash-receive','as'=>'receive.'],function (){
        Route::get('/cash-receive','Gl\CashReceiveController@index')->name('index');
        Route::post('/cash-receive','Gl\CashReceiveController@store')->name('cash-receive-store');
        //Route::post('/credit-acc-datalist', 'Gl\CashReceiveController@creditBankAccDatatableByFunc')->name('credit-acc-datalist');
        Route::post('/credit-acc-datalist', 'Gl\CashReceiveController@creditBankAccDatatable')->name('credit-acc-datalist');
    });

    Route::group(['name'=>'cash-payment','as'=>'payment.'],function (){
        Route::get('/cash-payment','Gl\CashPaymentController@index')->name('index');
        Route::post('/cash-payment','Gl\CashPaymentController@store')->name('cash-payment-store');
        //Route::post('/debit-acc-datalist', 'Gl\CashPaymentController@debitBankAccDatatableByFunc')->name('debit-acc-datalist');
        Route::post('/debit-acc-datalist', 'Gl\CashPaymentController@debitBankAccDatatable')->name('debit-acc-datalist');
    });

    Route::group(['name'=>'cash-transfer','as'=>'cash-transfer.'],function (){
        Route::get('/cash-transfer','Gl\CashTransferController@index')->name('index');
        Route::post('/cash-transfer', 'Gl\CashTransferController@store')->name('store');
    });

    Route::group(['name'=>'transaction','as'=>'transaction.'],function (){
        Route::get('/transaction','Gl\TransactionController@index')->name('index');
        Route::any('/transaction-mst-search-list', 'Gl\TransactionController@searchTransactionsMst')->name('transaction-mst-search-list');
        Route::any('/transaction-mst-by-dtl-search-list', 'Gl\TransactionController@searchTransactionsDtl')->name('transaction-mst-by-dtl-search-list');
        Route::get('/download-attachment/{trans_doc_file_id}','Gl\TransactionController@downloadAttachment')->name('download-attachment');
        Route::get('/transaction-edit/{id}','Gl\TransactionController@edit')->name('edit');
        Route::post('/transaction-ref-update', 'Gl\TransactionController@transactionUpdate')->name('update');
    });

    Route::group(['name'=>'reverse-journal','as'=>'reverse-journal.'],function (){
        Route::get('/reverse-journal','Gl\ReverseJournalController@index')->name('index');
        Route::post('/reverse','Gl\ReverseJournalController@reverseJournal')->name('reverse');
        Route::any('/reverse-journal-mst-search-list', 'Gl\ReverseJournalController@searchTransactionsMst')->name('reverse-journal-mst-search-list');
        Route::any('/reverse-journal-mst-by-dtl-search-list', 'Gl\ReverseJournalController@searchTransactionsDtl')->name('reverse-journal-mst-by-dtl-search-list');
        Route::get('/attachment-download/{trans_doc_file_id}','Gl\ReverseJournalController@downloadAttachment')->name('attachment-download');
    });

    Route::group(['name'=>'journal-voucher','as'=>'journal.'],function (){
        Route::get('/journal-voucher','Gl\JournalVoucherController@index')->name('index');
        Route::post('/journal-voucher','Gl\JournalVoucherController@store')->name('journal-voucher-store');
        Route::post('/journal-credit-acc-datalist', 'Gl\JournalVoucherController@creditBankAccDatatable')->name('journal-credit-acc-datalist');
        Route::post('/journal-debit-acc-datalist', 'Gl\JournalVoucherController@debitBankAccDatatable')->name('journal-debit-acc-datalist');
        Route::post('/journal-acc-datalist', 'Gl\JournalVoucherController@bankAccDatatable')->name('journal-acc-datalist');
    });

    Route::group(['name'=>'transaction-authorize','as'=>'transaction-authorize.'],function (){
        Route::get('/transaction-authorize/{filter?}','Gl\TransactionAuthorizeController@index')->name('index');
        Route::post('/transaction-authorize-mst-search-list', 'Gl\TransactionAuthorizeController@searchTransactionsAuthorizeMst')->name('transaction-authorize-mst-search-list');
        Route::post('/transaction-authorize-mst-by-dtl-search-list', 'Gl\TransactionAuthorizeController@searchTransactionsAuthorizeDtl')->name('transaction-authorize-mst-by-dtl-search-list');
        Route::get('/transaction-authorize-approval/{wkMapId}/{filter?}', 'Gl\TransactionAuthorizeController@approveRejectCancel')->name('approve-reject');
        Route::post('/transaction-authorize-approval-by-list', 'Gl\TransactionAuthorizeController@approveByList')->name('transaction-authorize-approval-by-list');
    });

    Route::group(['name'=>'cash-account-setup','as'=>'cash-account-setup.'], function (){
        Route::get('/cash-account-setup','Gl\CashAccountSetupController@index')->name('index');
        Route::post('/cash-account-setup','Gl\CashAccountSetupController@store')->name('store');
        //Route::post('/acc-datalist', 'Gl\CashAccountSetupController@bankAccDatatable')->name('credit-acc-datalist');

        Route::get('/cash-account-setup/{id}','Gl\CashAccountSetupController@edit')->name('edit');
        Route::put('/cash-account-setup/{id}','Gl\CashAccountSetupController@update')->name('update');
        Route::delete('/cash-account-setup/{id}','Gl\CashAccountSetupController@delete')->name('delete');
    });

    Route::group(['name'=>'revenue-account-setup','as'=>'revenue-account-setup.'], function (){
        Route::get('/revenue-account-setup','Gl\RevenueAccountSetupController@index')->name('index');
        Route::post('/revenue-account-setup','Gl\RevenueAccountSetupController@store')->name('store');
        Route::get('/revenue-account-setup/{id}','Gl\RevenueAccountSetupController@edit')->name('edit');
        Route::put('/revenue-account-setup/{id}','Gl\RevenueAccountSetupController@update')->name('update');
        Route::delete('/revenue-account-setup/{id}','Gl\RevenueAccountSetupController@delete')->name('delete');
    });

    Route::group(['name'=>'party-sub-ledger-setup','as'=>'party-sub-ledger-setup.'], function (){
        Route::get('/party-sub-ledger-setup','Gl\PartySubLedgerController@index')->name('index');
        Route::post('/party-sub-ledger-setup','Gl\PartySubLedgerController@store')->name('store');
        Route::get('/party-sub-ledger-setup/{id}','Gl\PartySubLedgerController@edit')->name('edit');
        Route::put('/party-sub-ledger-setup/{id}','Gl\PartySubLedgerController@update')->name('update');
        Route::get('/party-sub-ledger-setup-delete/{id}','Gl\PartySubLedgerController@delete')->name('delete');
    });

    Route::group(['name' => 'gl-balance-inquiry', 'as' => 'gl-balance-inquiry.'], function () {
        Route::get('/gl-balance-inquiry', 'Gl\GLBalanceInquiryController@index')->name('index');
    });

    Route::group(['name' => 'user-section-setup', 'as' => 'user-section-setup.'], function () {
        Route::get('/user-section-setup', 'Gl\SectionSetupController@index')->name('index');
        Route::post('/user-section-setup', 'Gl\SectionSetupController@store')->name('store');
        Route::get('/user-section-setup/{id}', 'Gl\SectionSetupController@edit')->name('edit');
        Route::put('/user-section-setup/{id}', 'Gl\SectionSetupController@update')->name('update');
        Route::post('/user-section-setup-datatable-list', 'Gl\SectionSetupController@dataTableList')->name('datatable-list');
        Route::get('/user-section-setup-setup-delete/{id}', 'Gl\SectionSetupController@delete')->name('delete');
    });

    //If route method not allowed
    Route::fallback(function () {
        abort(404);
    });

});
