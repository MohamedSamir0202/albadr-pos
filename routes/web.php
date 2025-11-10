<?php


use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\SafeController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\SafeTransactionController;
use App\Http\Controllers\Admin\WarehouseTransactionController;

//
Route::redirect('/', '/admin/home');

//
Auth::routes(['register' => false]);

//
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('items', ItemController::class);
    Route::get('clients/search', [ClientController::class, 'search'])->name
    ('clients.search');
    Route::resource('clients', ClientController::class);
    Route::resource('sales', SaleController::class);
    Route::get('admin/sales/{id}/download', [SaleController::class, 'downloadPdf'])
    ->name('sales.download');

    Route::resource('warehouses', WarehouseController::class);

    Route::get('warehouse-transactions', [WarehouseTransactionController::class, 'index'])
    ->name('warehouse-transactions.index');

    Route::resource('safes', SafeController::class);
        Route::prefix('safes/{safe_id}/transactions')->name('safe-transactions.')->group(function () {

        Route::get('/', [SafeTransactionController::class, 'index'])
            ->name('index');

        Route::get('/create', [SafeTransactionController::class, 'create'])
            ->name('create');

        Route::post('/', [SafeTransactionController::class, 'store'])
            ->name('store');

    });



});
