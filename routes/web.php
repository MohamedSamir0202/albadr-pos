<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SafeController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SafeTransactionController;
use App\Http\Controllers\Admin\WarehouseTransactionController;
use App\Http\Controllers\Admin\Settings\GeneralSettingsController;
use App\Http\Controllers\Admin\Settings\AdvancedSettingsController;

Route::redirect('/', 'admin/home');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Auth::routes(['register' => false]);

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
    Route::post('sales/{id}/pay-remaining', [SaleController::class, 'payRemaining'])
    ->name('sales.payRemaining');

    Route::resource('returns', ReturnController::class)->only('create', 'store');

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

    Route::group(['prefix' => 'settings'], function(){
        Route::get('general', [GeneralSettingsController::class, 'view'])
        ->name('settings.general.view');

        Route::put('general', [GeneralSettingsController::class, 'update'])
        ->name('settings.general.update');

        Route::get('advanced', [AdvancedSettingsController::class, 'view'])
        ->name('settings.advanced.view');

        Route::put('advanced', [AdvancedSettingsController::class, 'update'])
        ->name('settings.advanced.update');
    });

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
