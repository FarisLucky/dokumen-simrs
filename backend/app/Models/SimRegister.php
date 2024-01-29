<?php

namespace App\Models;

use App\Casts\DateTimeIdCast;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class SimRegister extends Model
{
    use Searchable;

    const NEW_IRJA = 0;

    const OLD_IRJA = 1;

    protected $connection = 'mysql_sim';

    protected $table = 'daftar';

    protected $primaryKey = 'register';

    public $casts = [
        'TANGGAL' => DateTimeIdCast::class,
    ];

    public function scopeJoinPasien($query)
    {
        return $query->join('m_pasien', 'm_pasien.mr', '=', 'daftar.mr')
            ->addSelect(DB::raw('m_pasien.NAMA as m_pasien_NAMA'), DB::raw('m_pasien.TGL_LAHIR as m_pasien_TGL_LAHIR'), DB::raw('m_pasien.SEX as m_pasien_SEX'));
    }
    public function scopeSelectRegister($query)
    {
        return $query->selectRaw('daftar.MR as daftar_MR, daftar.REGISTER as daftar_REGISTER, daftar.DIAGNOSA_MRS as daftar_DIAGNOSA_MRS, daftar.TANGGAL as daftar_TANGGAL');
    }

    public function scopeRanap($query)
    {
        return $query->where(function ($query) {
            $query->where('FLAG', SimRegister::NEW_IRJA)
                ->OrWhere('FLAG', SimRegister::OLD_IRJA);
        });
    }

    public function pasien(): HasOne
    {
        return $this->hasOne(SimPasien::class, 'MR', 'MR');
    }
}
