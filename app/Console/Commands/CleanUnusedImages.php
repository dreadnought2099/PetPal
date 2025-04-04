<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Pet;

class CleanUnusedImages extends Command
{
    protected $signature = 'images:cleanup';
    protected $description = 'Move unused images to a backup folder before deletion';

    public function handle()
    {
        $usedImages = Pet::pluck('pet_profile_path')->map(function ($path) {
            return storage_path("app/public/{$path}");
        })->toArray();

        $storagePath = storage_path('app/public');
        $backupPath = storage_path('app/backup_images');

        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        foreach (File::allFiles($storagePath) as $file) {
            $filePath = $file->getRealPath();

            if (!in_array($filePath, $usedImages)) {
                $backupFilePath = $backupPath . '/' . $file->getFilename();
                File::move($filePath, $backupFilePath);
                $this->info("Moved unused image to backup: {$backupFilePath}");
            }
        }

        $this->info('Cleanup complete! Unused images moved to backup.');
    }
}
