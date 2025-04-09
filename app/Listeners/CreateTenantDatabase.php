<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;


class CreateTenantDatabase
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
    // public function handle(TenantCreated $event): void
    // {
    //     xdebug_break();
    //     $tenant = $event->tenant;
    //     $databaseName = $tenant->database;

    //     DB::connection('central')->statement(
    //         "CREATE DATABASE `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    //     );
    // }
}
