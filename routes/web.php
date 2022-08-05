<?php

use Illuminate\Support\Facades\Route;
use corrective\ticketController;
use overtime\overtimeController;
use meter\summaryMeterController;
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


// Route::get('/', 'AuthController@showFormLogin')->name('loginform');
// Route::get('/', 'LoginController@index');
Route::get('/', 'LoginController@index')->name('login');

Route::get('/login', 'LoginController@index')->name('login');
Route::post('post-login', 'LoginController@postLogin'); 
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');


// Route::get('register', 'LoginController@register');
// Route::post('post-register', 'LoginController@postRegister'); 
Route::get('generate', 'LoginController@generate');
Route::put('put-generate', 'LoginController@putGenerate'); 
Route::get('logout', 'LoginController@logout')->name('logout');



    Route::get('/news/', function() {
        return view('news.index');
    })->name('news');
    // Route::middleware(['middleware' => 'auth'])->group(function () {
        
    // })

    Route::get('/meter/list-confirmation', 'meter\tenantConfirmationController@list_confirm')->name('list_meter_confirm')->middleware('auth');
    Route::post('/meter/grid-confirm/', 'meter\tenantConfirmationController@grid_confirmation')->name('grid_confirm')->middleware('auth');
    Route::post('/meter/confirm', 'meter\tenantConfirmationController@update')->name('meter.confirm')->middleware('auth');
    Route::get('/meter/confirmation/{param}', 'meter\tenantConfirmationController@index')->middleware('auth');
    Route::get('/meter/history/', 'meter\tenantConfirmationController@list_history')->name('list_meter_history')->middleware('auth');
    Route::post('/meter/grid-history/', 'meter\tenantConfirmationController@grid_history')->name('grid_history')->middleware('auth');
    Route::get('/meter/summary/', 'meter\summaryMeterController@index')->name('list_meter_summary')->middleware('auth');
    Route::post('/meter/load_data_summary/', 'meter\summaryMeterController@load_data')->name('load_data_summary')->middleware('auth');


    // Route::resource('/masteraccount', 'MasteraccountController');


    // route corrective request ticket
    Route::get('/corrective/request-ticket/', 'corrective\ticketController@create_ticket')->name('request_ticket')->middleware('auth');
    Route::get('/corrective/request-ticket/get-category-id/', 'corrective\ticketController@get_type_id')->middleware('auth');
    Route::resource('req-ticket', ticketController::class);

    // route corrective history ticket
    Route::get('/corrective/history-ticket/', 'corrective\ticketController@history_ticket')->name('history_ticket')->middleware('auth');
    Route::get('/corrective/history-ticket/delete/{tenant_ticket_id}','corrective\ticketController@destroy')->middleware('auth');
    Route::get('/corrective/history-ticket/show/{tenant_ticket_id}','corrective\ticketController@show')->middleware('auth');
    Route::post('/corrective/history-ticket/show/update/{tenant_ticket_id}', 'corrective\ticketController@update')->middleware('auth');
    Route::resource('updateStatus', ticketController::class);

Route::middleware('auth')->group(function () {
    // route overtime request ticket
    Route::get('/overtime/request-ticket/', 'overtime\overtimeController@create_ticket')->name('request_overtime');
    Route::get('/overtime/history-ticket/', 'overtime\overtimeController@history_ticket')->name('history_overtime');
    Route::get('/overtime/modify-ticket/', 'overtime\overtimeController@modify_ticket')->name('edit_overtime');
    Route::get('overtime/history-ticket/delete/{overtime_code}', 'overtime\overtimeController@destroy');
    Route::get('/overtime/get-time/{date}', 'overtime\overtimeController@get_time')->name('get_time');
    Route::get('/overtime/get-start-time/{start}', 'overtime\overtimeController@get_start_time')->name('get_start_time');
    Route::get('/overtime/get-duration/', 'overtime\overtimeController@get_duration')->name('get_duration');
    Route::post('/overtime/history-ticket/request-modify/{overtime_code}', 'overtime\overtimeController@request_modify');
    Route::get('/overtime/history-ticket/modify/{overtime_code}', 'overtime\overtimeController@modify_ticket');
    Route::post('/overtime/history-ticket/modify/modified/{overtime_code}', 'overtime\overtimeController@modified_ticket');
    Route::get('/overtime/history-ticket/get-ovt-details/{params}', 'overtime\overtimeController@get_ovt_details');
    Route::resource('overtime', overtimeController::class);


    Route::get('/preventive/schedule/', 'underconstructionController@index')->name('preventive_schedule');    
    Route::resource('preventive', scheduleController::class);

    Route::get('/billing/information/', 'underconstructionController@index')->name('billing_information');    
    Route::resource('billing', billingController::class);

    Route::get('/aggreement/information/', 'underconstructionController@index')->name('agreement_information');    
    Route::resource('agreement', aggreementController::class);

    Route::get('/vehicle/history/', 'underconstructionController@index')->name('vehicle_history');    
    Route::resource('vehicle', vehicleHistoryController::class);

    // route profile
    Route::get('/profile/{tenant_person}', 'profile\profileController@index');
    // route change password
    Route::get('/change_password/{tenant_person}', 'Auth\ResetPasswordController@index');
    Route::post('/submit_new_password', 'Auth\ResetPasswordController@submitNewPassword');

    Route::prefix('tracking-loading')->group(function () {
      Route::get('/', 'trackingLoading\historyController@index')->name('history_tracking_loading');
      Route::get('/listHistory', 'trackingLoading\historyController@listHistory');
      Route::get('/pdf', 'trackingLoading\historyController@printPdf');
      Route::get('/excel', 'trackingLoading\historyController@printExcel');
      Route::post('/create-update', 'trackingLoading\historyController@createOrUpdate');

        Route::get('/settings', 'trackingLoading\BmVisitTrackSettingController@index')->middleware('auth');
        Route::post("/settings", 'trackingLoading\BmVisitTrackSettingController@createOrUpdate')->middleware('auth');
        Route::post('/settings/datatable', 'trackingLoading\BmVisitTrackSettingController@getData')->middleware('auth');
        Route::delete('/settings/{id}', 'trackingLoading\BmVisitTrackSettingController@destroy')->middleware('auth');

        Route::get("/settings/size-types", "trackingLoading\BmVisitTrackSettingSizeTypeController@index")->middleware('auth');
        Route::post("/settings/size-types/create-update", "trackingLoading\BmVisitTrackSettingSizeTypeController@createOrUpdate")->middleware('auth');
        Route::post("/settings/size-types/datatable", "trackingLoading\BmVisitTrackSettingSizeTypeController@getData")->middleware('auth');
        Route::delete("/settings/size-types/{id}", "trackingLoading\BmVisitTrackSettingSizeTypeController@destroy")->middleware('auth');

        Route::prefix('not-scan-out')->group(function () {
            Route::get('/', 'trackingLoading\notScanOutController@index')->name('notscanout_tracking_loading');
            Route::get('/listHistory', 'trackingLoading\notScanOutController@listHistory');
            Route::get('/request-bak/{param}', 'trackingLoading\notScanOutController@requestBak');
            Route::post('/store', 'trackingLoading\notScanOutController@store');
            Route::get('/print-pdf', 'trackingLoading\notScanOutController@generate_bak');
        });

    });
});