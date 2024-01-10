<?php

namespace App\Services;

use App\Models\Filepond;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public $file;
    public $filename;
    public $disk;
    public $path;
    public $extension;

    public function __construct($file = null, $filename = null, $disk = 'public', $path = 'berkas/', $extension = null)
    {
        $this->file = $file;
        $this->filename = $filename;
        $this->disk = $disk;
        $this->path = $path;
        $this->extension = $extension;
    }

    public function upload($request, $pasien, $user)
    {
        $directory = Storage::disk($this->disk)->path($this->path);

        if (File::ensureDirectoryExists($directory)) {
            $directory = File::makeDirectory($directory);
        }

        Storage::disk($this->disk)->putFileAs($this->path, $this->file, $this->filename);

        return [
            'id_file' => null,
            'register' => $pasien->REGISTER,
            'filename' => $this->filename,
            'filepath' => $this->path,
            'extension' => $this->extension,
            'mimetypes' => $this->file->getClientMimeType(),
            'disk' => $this->disk,
            'created_by' => $user->id,
        ];
    }

    public function insertDB($payload)
    {
        return Filepond::insert($payload);
    }

    /**
     * Set the value of file
     */
    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Set the value of filename
     */
    public function setFilename($filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Set the value of disk
     */
    public function setDisk($disk): self
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Set the value of path
     */
    public function setPath($path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the value of extension
     */
    public function setExtension($extension): self
    {
        $this->extension = $extension;

        return $this;
    }
}
