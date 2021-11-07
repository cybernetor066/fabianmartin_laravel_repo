<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\DataController;

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

// Routes to different Views---------------------------------------------------------------

//testRoute
Route::get('/', function () {
    return view('welcome');
});

//Loading old HTML Demo
Route::get('demo', function () {

    return view('demo');

});

//simulates the different Role Concepts
Route::get('login', function () {

    return view('login');

});

//route to function overview (VD)
Route::get('overViewVd', function () {

    return view('overViewVd');

});

//route to function overview (VB)
Route::get('overViewVb', function () {

    return view('overViewVb');

});

//route to function overview (PB)
Route::get('overViewPb', function () {

    return view('overViewPb');

});

//route to function overview (Zulieferer OE)
Route::get('overViewOe', function () {

    return view('overViewOe');

});

//download area Vertriebsunterstützung (PB)
Route::get('downloadPb', function () {

    return view('downloadPb');

});

//download area Vertriebsfolien (VB)
Route::get('downloadVb', function () {

    return view('downloadVb');

});

//search area Sparkassenstammdaten
Route::get('searchSpk', function () {

    return view('searchSpk');

});

//content management area
Route::get('contentManagement', function () {

    return view('contentManagement');
});


//Routes to Controller Functions---------------------------------------------------------------

//load view vertriebsunterstützung with dynamic data (out of postgres db)
Route::get('downloadVd', [DataController::class,'loadVertriebsunterstuetzung']);
//retreive all Spk-Stammdaten from Postrges DB
Route::get('getAllSpkStammdaten', [DataController::class,'getAllSpkStammdaten']);
//search specific Sparkasse by name or BLZ
Route::post('serachSpkStammdaten', [DataController::class,'searchDbSpkStammdaten']);
//get all selected VB/VD Documents (with Bankleitzahl and document typ)
Route::post('downloadVdDocuments', [DataController::class,'downloadSpkDocuments']);
//get all selected PB Documents (only document typ)
Route::post('downloadVbDocuments', [DataController::class,'downloadSinglePdfFunction']);
//read the poolstructure from folderstructure and update the database
Route::post('readPoolStructure', [DataController::class,'readPoolStructure']);
//read the coretructure from folderstructure and update the database
Route::post('readPoolStructureCore', [DataController::class,'readPoolStructureCore']);