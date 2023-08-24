<?php

use App\Http\Controllers\{DashboardController, ItemController, LaporanController, MemberController, PembelianController, PenjualanController, ReturPembelianController, ReturPenjualanController, SuplierController};
use App\Models\Suplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

Route::get('/', function () {
    $date = date('H');
    if (!session()->get('theme')) {
        if ($date >= 18) {
            session(['theme' => 'dark']);
        } else {
            session(['theme' => 'light']);
        }
    }
    if (auth()->user()) {
        return to_route('dashboard');
    } else {
        return view('pages.auth.login');
    }
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // items
    Route::resource('/items', ItemController::class)->except('update', 'destroy');
    Route::post('/items/update', [ItemController::class, 'update'])->name('items.update');
    Route::post('/items/destroy', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/import', [ItemController::class, 'import'])->name('items.import');

    // members
    Route::resource('/members', MemberController::class)->except('update', 'destroy');
    Route::post('/members/update', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/destroy', [MemberController::class, 'destroy'])->name('members.destroy');

    // supliers
    Route::resource('/supliers', SuplierController::class)->except('update', 'destroy');
    Route::post('/supliers/update', [SuplierController::class, 'update'])->name('supliers.update');
    Route::post('/supliers/destroy', [SuplierController::class, 'destroy'])->name('supliers.destroy');

    // transactions
    Route::resource('/penjualan', PenjualanController::class)->except('update', 'destroy');
    Route::post('/penjualan/update', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::post('/penjualan/destroy', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    Route::post('/penjualan/destroyAll', [PenjualanController::class, 'destroyAll'])->name('penjualan.destroyAll');
    Route::get('/print', [PenjualanController::class, 'print'])->name('print');
    Route::get('/saveBarang', [PenjualanController::class, 'saveBarang'])->name('getBarang');
    Route::get('/print_nota', [PenjualanController::class, 'print_nota'])->name('print_nota');
    Route::post('/updatePrint', [PenjualanController::class, 'update_print'])->name('updatePrint');

    Route::resource('/retur_penjualan', ReturPenjualanController::class)->except('update', 'destroy');
    Route::post('/retur_penjualan/update', [ReturPenjualanController::class, 'update'])->name('retur.penjualan.update');
    Route::post('/retur_penjualan/destroy', [ReturPenjualanController::class, 'destroy'])->name('retur.penjualan.destroy');
    Route::post('/retur_penjualan/destroyAll', [ReturPenjualanController::class, 'destroyAll'])->name('retur.penjualan.destroyAll');

    Route::resource('/pembelian', PembelianController::class)->except('update', 'destroy');
    Route::post('/pembelian/update', [PembelianController::class, 'update'])->name('pembelian.update');
    Route::post('/pembelian/save', [PembelianController::class, 'save'])->name('pembelian.save');
    Route::resource('/retur_pembelian', ReturPembelianController::class);

    // reports
    Route::prefix('laporan')->as('laporan.')->group(function () {
        Route::controller(LaporanController::class)->group(function () {
            Route::get('/penjualan', 'penjualan')->name('penjualan');
            Route::get('/penjualan/export', 'export_penjualan')->name('export_penjualan');
            Route::get('/retur_penjualan', 'retur_penjualan')->name('retur_penjualan');
            Route::get('/retur_penjualan/export', 'export_retur_penjualan')->name('export_retur_penjualan');
            Route::get('/pembelian', 'pembelian')->name('pembelian');
            // Route::get('/retur_pembelian', 'returPembelian');
            Route::get('/laba_rugi', 'laba_rugi')->name('laba_rugi');
        });
    });

    Route::post('theme', function (Request $request) {
        if ($request->theme == 'dark') {
            session(['theme' => 'light']);
        } else {
            session(['theme' => 'dark']);
        }

        $theme = session()->get('theme');
        return response()->json([
            'status' => true,
            'msg' => 'success',
            'data' => $theme
        ], 200);
    })->name('setTheme');

    Route::get('get_suplier', function () {
        $data = Suplier::all();
        return response()->json([
            'status' => true,
            'supliers' => $data
        ], 200);
    })->name('getsuplier');

    Route::get('driver_printer', function () {
        $process = new Process(['C:/Program Files/python', '../getDriverPrinter.py']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = $process->getOutput();
        // $drivers = explode("\n", $output);
        return response()->json([
            'printers' => $output,
        ]);
    });
});

require __DIR__ . '/auth.php';
