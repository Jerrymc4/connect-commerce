<?php

namespace App\Listeners;


use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

class RunTenantMigrations
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //

    }

    /**
     * Handle the event.
     */
    public function handle(TenantCreated $event): void
    {
        $event->tenant->run(function () {
            Artisan::call('migrate', [
                '--path' => '/database/migrations/tenant',
                '--force' => true,
            ]);
        });
    }

}
