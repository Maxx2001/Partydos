<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ListFilesystemFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:list {disk : The filesystem disk to list files from (e.g., local, public, s3)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all files in the specified filesystem disk';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $disk = $this->argument('disk');

        if (!Storage::disk($disk)->exists('/')) {
            $this->error("The disk '{$disk}' does not exist or is not configured.");
            return CommandAlias::FAILURE;
        }

        $files = Storage::disk($disk)->allFiles();

        if (empty($files)) {
            $this->info("The '{$disk}' disk is empty.");
            return CommandAlias::SUCCESS;
        }

        $this->info("Files in the '{$disk}' disk:");
        foreach ($files as $file) {
            $this->line($file);
            $this->line("Size: " . Storage::disk($disk)->size($file) . " bytes");
        }

        return CommandAlias::SUCCESS;
    }
}
