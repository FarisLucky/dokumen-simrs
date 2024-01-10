<?php

use App\Http\Controllers\Web\PdfController;
use App\Models\Dokumen;
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
    echo bcrypt('rsgs@321');
    return view('welcome');
});

Route::get('/pasien/{id}/pdf', [PdfController::class, 'show'])->name('pdf.show');
Route::get('/pasien/{id}/thumbnail', [PdfController::class, 'showThumbnail'])->name('pdf.showThumbnail');
Route::get('/pasien/{id}/pdf-convert', [PdfController::class, 'convert'])->name('pdf.convert');
Route::get('/pasien/{id}/pdf-download', [PdfController::class, 'convertDomPdf'])->name('pdf.convertDomPdf');
Route::get('/folders/{mr}', function ($mr) {
    $doc = Dokumen::where('mr', $mr)->first();
    $directory = Storage::disk('public')->allFiles("/berkas/" . $doc->mr);
    dd($directory);
    echo $mr;
});
