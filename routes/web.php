<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//ambil semua data
Route::get('/students', [StudentController::class, 'index']);

//tambah data baru
Route::post('students/tambah-data', [StudentController::class, 'store']);

//generate token csrf
Route::get('generate-token', [StudentController::class, 'createToken']);

//ambil satu data spesifik
Route::get('/students/{id}', [StudentController::class, 'show']);

//mengubah data tertentu
Route::patch('/students/update/{id}', [StudentController::class, 'update']);

//menghapus data tertentu
Route::delete('/students/delete/{id}', [StudentController::class, 'destroy']);

//untuk menampilkan data yg sudah di hapus sementara oleh softdelete
Route::get('/students/show/traimage.pngsh', [StudentController::class, 'trash']);

//mengembalikan data spesifik yang sudah di hapus
Route::get('students/trash/restore/{id}', [StudentController::class, 'restore']);

//menghapus permanen data tertentu 
Route::get('/students/trash/delete/permanent/{id}', [StudentController::class, 'permanentDelete']);