<?php

namespace App\Services;

use App\Services\Contracts\StorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocalStorageService implements StorageService
{
    public function store(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }

    public function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }
}