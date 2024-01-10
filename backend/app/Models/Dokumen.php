<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumens';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tgl_mrs',
        'register',
        'mr',
        'pasien',
        'tgl_lahir',
        'nama',
        'nama_dok',
        'penunjang',
        'tgl_periksa',
        'sumber',
        'created_by',
        'created_by_name',
    ];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($dokumen) {
            foreach ($dokumen->filepond as $file) {
                $filename = explode('.', $file->filename);
                $newName = $file->filepath . '/' . optional($filename)[0] . '_delete.' . $file->extension;
                Storage::disk($file->disk)->move($file->filepath . '/' . $file->filename, $newName);
                $file->delete();
            }
        });
    }

    public function scopeJoinFilepond($query)
    {
        return $query->leftJoin('fileponds', 'fileponds.id_file', '=', 'dokumens.id');
    }

    public function scopeGetRegistByNoRm($query, $mr)
    {
        $query->where('mr', $mr)
            ->groupBy('register');
    }

    public function filepond(): HasMany
    {
        return $this->hasMany(Filepond::class, 'id_file');
    }
}
