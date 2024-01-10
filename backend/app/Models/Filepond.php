<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Filepond extends \RahulHaque\Filepond\Models\Filepond
{
    use HasFactory;

    protected $appends = [
        'path_doc'
    ];

    protected $fillable = [
        'id_file',
        'register',
        'filename',
        'filepath',
        'extension',
        'mimetypes',
        'disk',
        'created_by',
    ];

    public function scopeDeleteDokumen($query)
    {
        Storage::disk($this->disk)->delete($this->filepath . '/' . $this->filename);
    }

    public function getPathDocAttribute()
    {
        return Storage::disk($this->disk)->path($this->filepath . '/' . $this->filename);
    }
}
