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
    return view('welcome');
});

Route::name('barang')->prefix('data-barang')->controller(BarangController::class)->group(function(){
    Route::get('/','index');
    Route::post('/data','data')->name('.data');
    Route::get('/tambah-data','tambah')->name('.add');
    Route::get('/ubah/{id}/data','edit')->name('.edit');
    Route::get('/hapus/{id}/data','hapus')->name('.delete');
    Route::post('/simpan-data','simpan')->name('.save');
});
