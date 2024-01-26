<?php

use App\Http\Controllers\{
    DashboardController,
    LaporanController,
    PengeluaranController,
    SettingController,
    UserController,
};
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Branches\BranchesController;
use App\Http\Controllers\Unit\UnitsController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Purchase\PurchaseDetailController;
use App\Http\Controllers\Sales\SalesDetailsController;
use App\Http\Controllers\Sales\SalesController;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {

        // start category routes
        Route::get('/category/data', [CategoryController::class, 'data'])->name('category.data');
        Route::resource('/category', CategoryController::class);
        // end category routes

        // start unit routes
        Route::get('/unit/data', [UnitsController::class, 'data'])->name('unit.data');
        Route::resource('/unit', UnitsController::class);
        // end unit routes

        // start product routes
        Route::get('/product/data', [ProductController::class, 'data'])->name('product.data');
        Route::post('/product/delete-selected', [ProductController::class, 'deleteSelected'])->name('product.delete_selected');
        Route::post('/product/generate-barcode', [ProductController::class, 'barcode'])->name('product.barcode');
        Route::resource('/product', ProductController::class);
        // end product routes

        // start branch routes
        Route::get('/branch/data', [BranchesController::class, 'data'])->name('branch.data');
        Route::post('/branch/card-branch', [BranchesController::class, 'generateBranch'])->name('branch.card_branch');
        Route::resource('/branch', BranchesController::class);
        // end branch routes

        // start supplier routes
        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);
        // end supplier routes

        // start purchase route
        Route::get('/purchase/data', [PurchaseController::class, 'data'])->name('purchase.data');
        Route::get('/purchase/{id}/create', [PurchaseController::class, 'create'])->name('purchase.create');
        Route::resource('/purchase', PurchaseController::class)
            ->except('create');
        // end purchase route

        // start purchase detail route
        Route::get('/purchase_detail/{id}/data', [PurchaseDetailController::class, 'data'])->name('purchase_detail.data');
        Route::get('/purchase_detail/loadform/{discount}/{total}', [PurchaseDetailController::class, 'loadForm'])->name('purchase_detail.load_form');
        Route::resource('/purchase_detail', PurchaseDetailController::class)
            ->except('create', 'show', 'edit');
        // end purchase detail route

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        // start sales route
        Route::get('/sales/data', [SalesController::class, 'data'])->name('sales.data');
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/sales/{id}', [SalesController::class, 'show'])->name('sales.show');
        Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');
        // end sales route
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        // start sales route by role access
        Route::get('/transaction/show', [SalesController::class, 'create'])->name('transaction.create');
        Route::post('/transaction/store', [SalesController::class, 'store'])->name('transaction.store');
        Route::get('/transaction/sales', [SalesController::class, 'sales'])->name('transaction.sales');
        Route::get('/transaction/small', [SalesController::class, 'smallFormat'])->name('transaction.smPDF');
        Route::get('/transaction/large', [SalesController::class, 'pdfFormat'])->name('transaction.lgPDF');

        Route::get('/transaction/{id}/data', [SalesDetailsController::class, 'data'])->name('transaction.data');
        Route::get('/transaction/loadform/{total}/{received}', [SalesDetailsController::class, 'loadForm'])->name('transaction.load_form');
        Route::resource('/transaction_details', SalesDetailsController::class)
            ->except('create', 'show', 'edit');
        // end sales route by role access
    });



    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});
