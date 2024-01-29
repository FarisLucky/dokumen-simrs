<?php

namespace App\Models;

use App\Casts\DateTimeIdCast;
use Illuminate\Database\Eloquent\Model;

class SimPasien extends Model
{
    protected $connection = 'mysql_sim';

    protected $table = 'm_pasien';

    public $casts = [
        'TGL_LAHIR' => DateTimeIdCast::class,
    ];
}
