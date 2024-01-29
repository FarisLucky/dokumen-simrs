<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Dokumen;

class KunjunganPasienController extends Controller
{
    use ApiResponse;

    public function list($mr)
    {
        try {
            \DB::statement("SET SQL_MODE=''");

            $kunjungan = Dokumen::select('register', 'tgl_mrs')->where('mr', $mr)->groupBy('register', 'tgl_mrs')->get();

            return $this->okApiResponse($kunjungan, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
