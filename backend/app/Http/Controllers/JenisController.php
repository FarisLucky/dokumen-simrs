<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Jenis;

class JenisController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $user = auth()->user();
            $unit = $user->level;

            $jenis = Jenis::all();

            $jenis = $jenis->filter(function ($jenis) use ($unit) {
                return in_array($unit, $jenis->unit);
            })->values();

            return $this->okApiResponse($jenis, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
