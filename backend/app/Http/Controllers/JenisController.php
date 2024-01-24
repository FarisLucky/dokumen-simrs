<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Jenis;
use Illuminate\Support\Facades\DB;

class JenisController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $user = auth()->user();
            $unit = $user->role;

            $jenis = DB::select(DB::raw("select j.* from jenis j where find_in_set({$unit}, j.unit) = 0"));

            return $this->successApiResponse($jenis, 'Berhasil dimuat');
        } catch (\Throwable $th) {

            return $this->errorApiResponse($th->getMessage());
        }
    }
}
