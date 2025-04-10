<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinkTenantAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:link-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create symbolic links for tenant asset access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Linking tenant assets...');

        // Create a copy of the public build directory in the storage directory
        // which will be accessible via the 'public' disk for tenants
        $source = public_path('build');
        $destination = storage_path('app/public/build');

        // Create destination directory if it doesn't exist
        if (!File::isDirectory(dirname($destination))) {
            File::makeDirectory(dirname($destination), 0755, true);
        }

        // If the destination exists as a link or directory, remove it
        if (File::exists($destination)) {
            if (is_link($destination)) {
                unlink($destination);
            } else {
                File::deleteDirectory($destination);
            }
        }

        // Create a symlink from public/build to storage/app/public/build
        File::link($source, $destination);
        
        $this->info('Assets linked successfully!');
        
        // Make sure to run php artisan storage:link if it hasn't been run
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }
        
        $this->info('All tenant assets are now accessible!');
        
        return Command::SUCCESS;
    }
} 