<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface StorageService
{
    public function store(UploadedFile $file, string $folder): string;

    public function delete(string $path): bool;
}
