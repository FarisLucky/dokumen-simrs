<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimPasien extends Model
{
    protected $connection = 'mysql_sim';

    protected $table = 'm_pasien';
}
