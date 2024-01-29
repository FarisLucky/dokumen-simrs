<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KunjunganPasienController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Web\PdfController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('dokumens', DokumenController::class)->names('dokumen');
    Route::get('dokumens/by-mr/{mr}', [DokumenController::class, 'registerByRm'])
        ->name('dokumens.registerByRm');
    Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::resource('pasiens', PasienController::class)
        ->names('pasien')
        ->except('show');
    Route::get('pasiens/{mr}/{type}', [PasienController::class, 'show'])
        ->name('pasien.show');
    Route::get('history-dokumens', [HistoryController::class, 'index'])
        ->name('history.index');
    Route::get('img-url/generate/{id_file}', [PdfController::class, 'generateId'])
        ->name('pdf.generateId');
    Route::put('update-password', [LoginController::class, 'updatePassword'])
        ->name('login.update_password');
    Route::get('jenis', [JenisController::class, 'index'])
        ->name('jenis.index');
});
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('pdf.generateId');

// Route::middleware('api_token')->group(function () {

Route::get('img-url/generate/{id_file}/open', [PdfController::class, 'generateId'])
    ->name('pdf.generateId');
Route::get('pasiens-open', [PasienController::class, 'index'])
    ->name('pasien.index.open');
Route::get('pasiens-open/{pasien}/{type}', [PasienController::class, 'show'])
    ->name('pasien.show.open');
Route::get('dokumens-open', [DokumenController::class, 'index'])
    ->name('dokumen.open');
Route::get('dokumens-open/{id}/open', [DokumenController::class, 'show'])
    ->name('dokumen.show.open');
Route::get('dokumens-open/by-mr/{mr}', [DokumenController::class, 'registerByRm'])
    ->name('dokumen.showByMr.open');
/**
 *  List Kunjungan
 */
Route::get('list-kunjungan/{mr}/pasien', [KunjunganPasienController::class, 'list'])
    ->name('dokumen.showByMr.open');

/**
 *  URL History Dokumen
 */
Route::get('history-dokumens/by-reg/{reg?}', [HistoryController::class, 'dokumensByReg'])
    ->name('history-dokumens');
Route::get('history-dokumens/by-mr/{mr?}', [HistoryController::class, 'dokumensByMr'])
    ->name('history-dokumens.by-mr');
// });

/**
 * Authentication route
 *
 **/
Route::post('login', [LoginController::class, 'login'])->name('auth.login');
