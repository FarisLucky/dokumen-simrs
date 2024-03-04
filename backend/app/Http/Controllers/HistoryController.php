<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Dokumen;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{

    use ApiResponse;

    public function index()
    {
        try {

            $pasien = Dokumen::joinFilepond()->get();

            return $this->okApiResponse($pasien);
        } catch (\Throwable $th) {
            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function dokumensByReg($reg = null)
    {
        try {

            $dokumens = Dokumen::with('filepond');

            if (!is_null($reg)) {
                $dokumens->where('register', "$reg");
            }

            return $this->okApiResponse($dokumens->latest()->get(), 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }

    public function dokumensByMr($mr = null)
    {
        try {

            $page = request('page');
            $perPage = request('per_page');

            DB::statement('SET SQL_MODE=""');

            $dokumens = Dokumen::select('mr', 'register', 'nama');

            if (!is_null($mr)) {
                $dokumens->where('mr', 'LIKE', "%{$mr}%");
            }

            $dokumens->groupBy('register');

            return $this->okApiResponse($dokumens->latest()->paginate($perPage), 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
}
