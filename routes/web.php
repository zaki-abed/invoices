<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/invoices', 'InvoiceController');

Route::resource('/sections', 'SectionController');

Route::resource('/products', 'ProductController');

Route::get('/section/{id}', 'InvoiceController@getProducts');

Route::get('/invoice-details/{id}', 'InvoicesDetailsController@show')->name('invoice-details');

Route::get('view-file/{invoice_number}/{file_name}', 'InvoicesDetailsController@openFile');

Route::get('download-file/{invoice_number}/{file_name}', 'InvoicesDetailsController@getFile');

Route::post('delete-file', 'InvoicesDetailsController@destroy')->name('delete-file');

Route::post('create-attachment', 'InvoicesAttachmentsController@store')->name('create-attachment');

Route::get('edit-invoice/{id}', 'InvoiceController@edit')->name('edit-invoice');

Route::post('update-invoice/{id}', 'InvoiceController@update')->name('invoices.update');

Route::post('delete-invoice', 'InvoiceController@destroy')->name('invoices.destroy');

Route::get('edit-status/{id}', 'InvoiceController@editStatus')->name('edit_status');

Route::post('update-status/{id}', 'InvoiceController@updateStatus')->name('update_status');

Route::get('paid-invoices', 'InvoiceController@paidInvoices')->name('paid-invoices');

Route::get('unpaid-invoices', 'InvoiceController@unpaidInvoices')->name('unpaid-invoices');

Route::get('partially-paid-invoices', 'InvoiceController@partiallyPaidInvoices')->name('partially-paid-invoices');

Route::get('invoices-archives', 'InvoiceController@invoicesArchives')->name('invoices-archives');

Route::post('destroy-invoices', 'InvoiceController@invoicesDestroy')->name('destroy-invoices');

Route::post('unarchive-invoice', 'InvoiceController@unarchiveInvoice')->name('unarchive-invoice');

Route::get('print-invoice/{id}', 'InvoiceController@printInvoice')->name('print-invoice');

Route::get('invoices-export', 'InvoiceController@export')->name('invoices-export');

// Route::group(['middleware' => ['auth']], function() {
//     Route::resource('roles','RoleController');
//     Route::resource('users','UserController');
// });
Route::resource('roles','RoleController');
    Route::resource('users','UserController');
Route::get('reports-invoices', 'ReportInvoiceController@index')->name('reports-invoices');

Route::post('search-invoices', 'ReportInvoiceController@search')->name('search-invoices');

Route::get('report-customer', 'ReportInvoiceController@indexCustomer')->name('report-customer');

Route::post('search-customer', 'ReportInvoiceController@searchCustomer')->name('search-customer');

Route::get('make-all-read', 'InvoiceController@makeAllRead')->name('make-all-read');
//
// Route::get('/{page}', 'AdminController@index');
