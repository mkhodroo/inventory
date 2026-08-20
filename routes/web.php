<?php

use Illuminate\Support\Facades\Route;
use StockFlow\Inventory\Http\Controllers\CategoryController;
use StockFlow\Inventory\Http\Controllers\EntryController;
use StockFlow\Inventory\Http\Controllers\EntryReasonController;
use StockFlow\Inventory\Http\Controllers\ExitReasonController;
use StockFlow\Inventory\Http\Controllers\MovementController;
use StockFlow\Inventory\Http\Controllers\MyDeliveryController;
use StockFlow\Inventory\Http\Controllers\ProductController;
use StockFlow\Inventory\Http\Controllers\ReceiverController;
use StockFlow\Inventory\Http\Controllers\SettlementController;
use StockFlow\Inventory\Http\Controllers\SettlementReasonController;
use StockFlow\Inventory\Http\Controllers\StockController;
use StockFlow\Inventory\Http\Controllers\StockExitController;
use StockFlow\Inventory\Http\Controllers\WarehouseController;

Route::prefix(config('inventory.route_prefix'))->name('inventory.')->middleware(config('inventory.middleware'))->group(function () {
    // My Deliveries Routes
    Route::prefix('my-deliveries')->name('my-deliveries.')->group(function () {
        Route::get('/', [MyDeliveryController::class, 'index'])->name('index');
        Route::get('/{receiver}', [MyDeliveryController::class, 'show'])->name('show');
    });

    // Receiver Routes
    Route::prefix('receivers')->name('receivers.')->group(function () {
        Route::get('/', [ReceiverController::class, 'index'])->name('index');
        Route::get('/create', [ReceiverController::class, 'create'])->name('create');
        Route::post('/', [ReceiverController::class, 'store'])->name('store');
        Route::get('/{receiver}', [ReceiverController::class, 'show'])->name('show');
        Route::get('/{receiver}/edit', [ReceiverController::class, 'edit'])->name('edit');
        Route::put('/{receiver}', [ReceiverController::class, 'update'])->name('update');
        Route::delete('/{receiver}', [ReceiverController::class, 'destroy'])->name('destroy');
        Route::put('/{receiver}/toggle-active', [ReceiverController::class, 'toggleActive'])->name('toggle-active');
    });

    // Settlement Reasons Routes
    Route::prefix('settlement-reasons')->name('settlement-reasons.')->group(function () {
        Route::get('/', [SettlementReasonController::class, 'index'])->name('index');
        Route::post('/', [SettlementReasonController::class, 'store'])->name('store');
        Route::put('/{settlementReason}', [SettlementReasonController::class, 'update'])->name('update');
        Route::delete('/{settlementReason}', [SettlementReasonController::class, 'destroy'])->name('destroy');
    });

    // Settlement Routes
    Route::prefix('settlements')->name('settlements.')->group(function () {
        Route::get('/', [SettlementController::class, 'index'])->name('index');
        Route::get('/create', [SettlementController::class, 'create'])->name('create');
        Route::post('/', [SettlementController::class, 'store'])->name('store');
        Route::get('/{settlement}/edit', [SettlementController::class, 'edit'])->name('edit');
        Route::put('/{settlement}', [SettlementController::class, 'update'])->name('update');
        Route::delete('/{settlement}', [SettlementController::class, 'destroy'])->name('destroy');
        Route::get('/{settlement}/document', [SettlementController::class, 'downloadDocument'])->name('download-document');
    });

    // Category Routes
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Product Routes
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Warehouse Routes
    Route::prefix('warehouses')->name('warehouses.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseController::class, 'create'])->name('create');
        Route::post('/', [WarehouseController::class, 'store'])->name('store');
        Route::get('/{warehouse}', [WarehouseController::class, 'show'])->name('show');
        Route::get('/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('edit');
        Route::put('/{warehouse}', [WarehouseController::class, 'update'])->name('update');
        Route::delete('/{warehouse}', [WarehouseController::class, 'destroy'])->name('destroy');
    });

    // Entry Reasons Routes
    Route::prefix('entry-reasons')->name('entry-reasons.')->group(function () {
        Route::get('/', [EntryReasonController::class, 'index'])->name('index');
        Route::post('/', [EntryReasonController::class, 'store'])->name('store');
        Route::put('/{entryReason}', [EntryReasonController::class, 'update'])->name('update');
        Route::delete('/{entryReason}', [EntryReasonController::class, 'destroy'])->name('destroy');
    });

    // Exit Reasons Routes
    Route::prefix('exit-reasons')->name('exit-reasons.')->group(function () {
        Route::get('/', [ExitReasonController::class, 'index'])->name('index');
        Route::post('/', [ExitReasonController::class, 'store'])->name('store');
        Route::put('/{exitReason}', [ExitReasonController::class, 'update'])->name('update');
        Route::delete('/{exitReason}', [ExitReasonController::class, 'destroy'])->name('destroy');
    });

    // Entry Routes
    Route::prefix('entries')->name('entries.')->group(function () {
        Route::get('/', [EntryController::class, 'index'])->name('index');
        Route::get('/create', [EntryController::class, 'create'])->name('create');
        Route::post('/', [EntryController::class, 'store'])->name('store');
        Route::get('/{entry}/edit', [EntryController::class, 'edit'])->name('edit');
        Route::put('/{entry}', [EntryController::class, 'update'])->name('update');
        Route::delete('/{entry}', [EntryController::class, 'destroy'])->name('destroy');
    });

    // Exit Routes
    Route::prefix('exits')->name('exits.')->group(function () {
        Route::get('/', [StockExitController::class, 'index'])->name('index');
        Route::get('/create', [StockExitController::class, 'create'])->name('create');
        Route::post('/', [StockExitController::class, 'store'])->name('store');
        Route::get('/{exit}/edit', [StockExitController::class, 'edit'])->name('edit');
        Route::put('/{exit}', [StockExitController::class, 'update'])->name('update');
        Route::delete('/{exit}', [StockExitController::class, 'destroy'])->name('destroy');
    });

    // Stock Routes
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/{product}', [StockController::class, 'show'])->name('show');
    });

    // Movement Routes
    Route::prefix('movements')->name('movements.')->group(function () {
        Route::get('/', [MovementController::class, 'index'])->name('index');
    });
});
