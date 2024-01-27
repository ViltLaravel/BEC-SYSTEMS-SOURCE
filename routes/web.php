<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    LaporanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
    UserController,
};
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
        Route::get('/category/data', [KategoriController::class, 'data'])->name('category.data');
        Route::resource('/category', KategoriController::class);

        Route::get('/items/data', [ProdukController::class, 'data'])->name('items.data');
        Route::post('/items/delete-selected', [ProdukController::class, 'deleteSelected'])->name('items.delete_selected');
        Route::post('/items/generate-barcode', [ProdukController::class, 'cetakBarcode'])->name('items.generate_barcode');
        Route::resource('/items', ProdukController::class);

        Route::get('/branch/data', [MemberController::class, 'data'])->name('branch.data');
        Route::post('/branch/generate-branch', [MemberController::class, 'cetakMember'])->name('branch.generate_branch');
        Route::resource('/branch', MemberController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/expense/data', [PengeluaranController::class, 'data'])->name('expense.data');
        Route::resource('/expense', PengeluaranController::class);

        Route::get('/purchase/data', [PembelianController::class, 'data'])->name('purchase.data');
        Route::get('/purchase/{id}/create', [PembelianController::class, 'create'])->name('purchase.create');
        Route::resource('/purchase', PembelianController::class)
            ->except('create');

        Route::get('/purchase_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('purchase_detail.data');
        Route::get('/purchase_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('purchase_detail.load_form');
        Route::resource('/purchase_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/sales/data', [PenjualanController::class, 'data'])->name('sales.data');
        Route::get('/sales', [PenjualanController::class, 'index'])->name('sales.index');
        Route::get('/sales/{id}', [PenjualanController::class, 'show'])->name('sales.show');
        Route::delete('/sales/{id}', [PenjualanController::class, 'destroy'])->name('sales.destroy');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/transaction/create', [PenjualanController::class, 'create'])->name('transaction.create');
        Route::post('/transaction/store', [PenjualanController::class, 'store'])->name('transaction.store_sales');
        Route::get('/transaction/sales', [PenjualanController::class, 'sales'])->name('transaction.sales');
        Route::get('/transaction/sm_pdf', [PenjualanController::class, 'notaKecil'])->name('transaction.sm_pdf');
        Route::get('/transaction/lg_pdf', [PenjualanController::class, 'notaBesar'])->name('transaction.lg_pdf');

        Route::get('/transaction/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaction.data');
        Route::get('/transaction/loadform/{total}', [PenjualanDetailController::class, 'loadForm'])->name('transaction.load_form');
        Route::resource('/transaction', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');
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
