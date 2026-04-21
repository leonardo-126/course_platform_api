<?php

namespace App\Actions\Course;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class CourseThumbnailService
{
    private const DISK = 'public';
    private const FOLDER = 'courses/thumbnails';

    public function store(UploadedFile $file): string
    {
        $path = $file->store(self::FOLDER, self::DISK);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk(self::DISK);

        return $disk->url($path);
    }

    public function delete(?string $url): void
    {
        if ($url === null) {
            return;
        }
        $path = str_replace(Storage::url(''), '', $url);
        Storage::delete($path);
    }
}