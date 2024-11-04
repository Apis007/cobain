<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\http\Controllers\LoginController;


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
    return view('login.index');
});

Route::get('/login', [LoginController::class, 'halaman_login']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/pelanggan', function () {
    return view('pelanggan.view');
})->middleware('auth');

Route::name('teknisi')->prefix('data-teknisi')->controller(TeknisiController::class)->group(function(){
    Route::get('/','index');
    Route::post('/data','data')->name('.data');
    Route::get('/tambah-data','tambah')->name('.add');
    Route::get('/ubah/{id}/data','edit')->name('.edit');
    Route::get('/hapus/{id}/data','hapus')->name('.delete');
    Route::post('/simpan-data','simpan')->name('.save');
});

Route::name('pelanggan')->prefix('data-pelanggan')->controller(PelangganController::class)->group(function(){
    Route::get('/','index');
    Route::post('/data','data')->name('.data');
    Route::get('/tambah-data','tambah')->name('.add');
    Route::get('/ubah/{id}/data','edit')->name('.edit');    
    Route::get('/hapus/{id}/data','hapus')->name('.delete');
    Route::get('/detail/{id}/data','detail')->name('.detail');
    Route::post('/simpan-data','simpan')->name('.save');
});

Route::name('redaman')->prefix('data-redaman')->controller(RedamanController::class)->group(function(){
    Route::get('/','index');
    Route::post('/data','data')->name('.data');
    Route::get('/tambah-data','tambah')->name('.add');
    Route::post('/redaman/import','import')->name('.import');
    Route::post('/simpan-data','simpan')->name('.save');
});
