<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Nivel2Controller;
use App\Http\Controllers\Nivel3Controller;
use App\Models\Nivel2;
use App\Models\Nivel3;
use App\Events\Level2;
use App\Events\Level3;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return view('welcome');
});



Route::post('/send-diag2-update', function (Request $request) {
    event(
        new Level2($request->input('json')),
    );

    return ["success" => true];
});

Route::post('/send-diag3-update', function (Request $request) {
    event(
        new Level3($request->input('json')),
    );

    return ["success" => true];
});

Route::middleware(['auth'],)->group(function(){
    //Session
    Route::resource('sessions', SessionsController::class);
    Route::resource('nivel2', Nivel2Controller::class);
    Route::resource('nivel3', Nivel3Controller::class);
});


Route::post('/update-nivel2', function (Request $request) {
    $nivel2 = Nivel2::findOrFail($request->input('title_slug'));
    $nivel2->json_data = $request->input('json_data');
    $nivel2->save();

    return ["success" => true];
});

Route::post('/update-nivel3', function (Request $request) {
    $nivel3 = Nivel3::findOrFail($request->input('title_slug'));
    $nivel3->json_data = $request->input('json_data');
    $nivel3->save();

    return ["success" => true];
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
