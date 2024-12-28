<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearFilesystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:clear {disk : The filesystem disk to clear (e.g., local, public, s3)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all files from the specified filesystem disk';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (app()->environment(['production', 'testing'])) {
            return CommandAlias::FAILURE;
        }

        $disk = $this->argument('disk');

        if (!Storage::disk($disk)->exists('/')) {
            $this->error("The disk '{$disk}' does not exist or is not configured.");
            return CommandAlias::FAILURE;
        }

        $files = Storage::disk($disk)->allFiles();
        $directories = Storage::disk($disk)->allDirectories();

        // Delete all files
        foreach ($files as $file) {
            Storage::disk($disk)->delete($file);
        }

        // Delete all directories
        foreach ($directories as $directory) {
            Storage::disk($disk)->deleteDirectory($directory);
        }

        $this->info("All files and directories in the '{$disk}' disk have been cleared.");

        if (Storage::disk($disk)->exists('.cache')) {
            Storage::disk($disk)->deleteDirectory('.cache');
            $this->info("The .cache directory has been deleted.");
        }

        return CommandAlias::SUCCESS;
    }
}
