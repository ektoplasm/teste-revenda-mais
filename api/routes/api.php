<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/')->group(function () {
    Route::resource('suppliers', SupplierController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'suppliers.index',
            'store' => 'suppliers.store',
            'update' => 'suppliers.update',
            'destroy' => 'suppliers.destroy',
        ]);

    Route::get('suppliers/search/{cnpj}', [SupplierController::class, 'searchDataByCnpj'])->name('suppliers.searchCnpj');
});
