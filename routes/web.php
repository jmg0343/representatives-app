<?php

use App\Http\Controllers\ElectionsController;
use App\Http\Controllers\RepresentativesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

URL::forceScheme('https');

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function () {
    return view('/pages/home');
});

Route::get('representatives', [RepresentativesController::class, 'representatives'])->name('reps');
Route::post('representatives', [RepresentativesController::class, 'findReps'])->name('reps.find');
Route::get('elections', [ElectionsController::class, 'elections'])->name('elections');
Route::post('elections/{id}', [ElectionsController::class, 'electionInfo'])->name('elections.info');
