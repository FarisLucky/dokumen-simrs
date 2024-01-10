<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Dokumen;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $results = [];

            $results['doc_today'] = Dokumen::whereDate('created_at', now()->format('Y-m-d'))->count();
            $results['doc_all'] = Dokumen::count();

            return $this->okApiResponse($results);
        } catch (\Throwable $th) {

            return $this->errorApiResponse([
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
}
