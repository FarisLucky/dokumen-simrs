<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\SimPasien;
use App\Models\SimRegister;


class PasienController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $page = request('per_page');
            $columnKeyFilter = request('column_key');
            $columnValFilter = request('column_val');
            $sortBy = request('field');
            $sortType = request('type');

            $pasien = SimRegister::selectRegister()->joinPasien();

            $pasien->when(!is_null($columnKeyFilter) && !is_null($columnValFilter), function ($query) use ($columnKeyFilter, $columnValFilter) {
                $query->filter($columnKeyFilter, $columnValFilter);
            });

            $pasien->when(!is_null($sortBy) && !is_null($sortType), function ($query) use ($sortBy, $sortType) {
                if ($sortType !== 'none') {
                    $query->orderBy($sortBy, $sortType);
                }
            }, function ($query) {
                $query->orderBy('daftar.crtime', 'DESC');
            });

            $pasien->where(function ($query) {
                $query->where('FLAG', SimRegister::NEW_IRJA)
                    ->OrWhere('FLAG', SimRegister::OLD_IRJA);
            });

            $oneYear = now()->subYears(1);

            $pasien->whereDate('TANGGAL', '>', $oneYear);

            return $this->okApiResponse($pasien->paginate($page));
        } catch (\Throwable $th) {
            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function ranap()
    {
        try {

            $page = request('per_page');
            $columnKeyFilter = request('column_key');
            $columnValFilter = request('column_val');
            $sortBy = request('field');
            $sortType = request('type');

            $pasien = SimRegister::selectRegister()->joinPasien();

            $pasien->when(!is_null($columnKeyFilter) && !is_null($columnValFilter), function ($query) use ($columnKeyFilter, $columnValFilter) {
                $query->filter($columnKeyFilter, $columnValFilter);
            });

            $pasien->when(!is_null($sortBy) && !is_null($sortType), function ($query) use ($sortBy, $sortType) {
                if ($sortType !== 'none') {
                    $query->orderBy($sortBy, $sortType);
                }
            }, function ($query) {
                $query->orderBy('daftar.crtime', 'DESC');
            });

            $pasien->ranap();

            $oneYear = now()->subYears(1);

            $pasien->whereDate('TANGGAL', '>', $oneYear);

            return $this->okApiResponse($pasien->paginate($page));
        } catch (\Throwable $th) {
            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function show($params, $type)
    {
        try {

            $pasien = SimPasien::where('mr', $params)
                ->first();

            if ($type === 'register') {
                $pasien = SimRegister::with('pasien')
                    ->where('register', $params)
                    ->first();
            }

            return $this->okApiResponse($pasien);
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
}
