<?php

use App\Models\User;

return [
    /*
    |--------------------------------------------------------------------------
    | Inventory Package Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the inventory management package here.
    |
    */

    'prefix' => env('INVENTORY_TABLE_PREFIX', 'inventory_'),

    'route_prefix' => env('INVENTORY_ROUTE_PREFIX', 'inventory'),

    'middleware' => ['web', 'auth'],

    'user_model' => User::class,
];
