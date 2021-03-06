<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/po/code', function (){
    return \App\Util\POCodeGenerator::generateCode();
})->name('api.po.code');

Route::get('/so/code', function (){
    return \App\Util\SOCodeGenerator::generateCode();
})->name('api.so.code');

Route::group(['prefix' => 'warehouse'], function ()
{
    Route::group(['prefix' => 'outflow'], function ()
    {
        Route::get('/so/{id?}', 'WarehouseOutflowController@getWarehouseSOs')->name('api.warehouse.outflow.so');
    });

    Route::group(['prefix' => 'inflow'], function ()
    {
        Route::get('/po/{id?}', 'WarehouseInflowController@getWarehousePOs')->name('api.warehouse.inflow.po');
    });
});

Route::group(['prefix' => 'customer'], function ()
{
    Route::get('search/{param?}', 'CustomerController@searchCustomers')->name('api.customer.search');
});

Route::group(['prefix' => 'phone_provider'], function()
{
    Route::get('search/{param?}', 'PhoneProviderController@getPhoneProviderByDigit')->name('api.phone_provider.search');
});