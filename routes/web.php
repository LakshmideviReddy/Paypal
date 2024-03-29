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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('paywithpaypal');
});

Route::post('paypal','PaymentController@payWithpaypal');
Route::get('status','PaymentController@getPaymentStatus');

Route::get('site-register', 'SiteAuthController@siteRegister');
Route::post('site-register', 'SiteAuthController@siteRegisterPost');
Route::get('sample-restful-apis', function()
{
    return array(
      1 => "expertphp",
      2 => "demo"
    );
});
