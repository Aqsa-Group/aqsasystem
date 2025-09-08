<?php

use App\Http\Controllers\ContractPrintController;
use App\Http\Controllers\ShopkeeperPrintController;
use App\Http\Controllers\SignedImagePdfController;
use App\Http\Controllers\WithdrawPrint;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffContractPrintController;
use App\Http\Controllers\DepositLogPrintController;
use App\Http\Controllers\AccountingPrintController;
use App\Http\Controllers\AmountController;
use App\Http\Controllers\InventoryPrintController;
use App\Http\Controllers\OutsideController;
use App\Http\Controllers\PrintBoothContract;
use App\Http\Controllers\SalaryPrintController;
use App\Http\Controllers\WarehousePrintController;
use App\Http\Controllers\Sarafi\Auth\CustomController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\PrintContract;
use App\Http\Controllers\printLoan;








Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('contracts.rent');
});


Route::get('/contract/{id}/print', [ContractPrintController::class, 'generate'])->name('contract.print');
Route::get('/contract/{id}/p', [PrintContract::class , 'generate'])->name('contract.print2');
Route::get('/contract/{id}/booth', [PrintBoothContract::class , 'generate'])->name('contract.printbooth');


Route::get('/recipt/{id}/print', [WithdrawPrint::class, 'generate'])->name('recipt.print');


Route::get('/staff/{id}/contract-print', [StaffContractPrintController::class, 'generate'])->name('staff.contract.print');


Route::get('/contract/signed-download/{document}', [ContractPrintController::class, 'downloadSigned'])
    ->name('contract.signed.download');

Route::get('/documents/{document}/signed-image-pdf', [\App\Http\Controllers\SignedImagePdfController::class, 'download'])->name('contract.signed-image.download');



Route::get('/deposit-log/{id}/print', [DepositLogPrintController::class, 'generate'])->name('deposit-log.print');


Route::get('/accounting/{id}/print', [AccountingPrintController::class, 'generate'])->name('accounting.print');

Route::get('/salary/print/{id}', [SalaryPrintController::class, 'generate'])->name('salary.print');
Route::get('/loan/print/{id}', [printLoan::class, 'generate'])->name('loan.print');
Route::get('/payment/print/{id}', [AmountController::class, 'generate'])->name('amount.print');
Route::get('/outside/print/{id}', [OutsideController::class, 'generate'])->name('outside.print');




// Import system route 

Route::get('/warehouse/print', [WarehousePrintController::class, 'generate'])
    ->name('warehouse.print');
    Route::get('/inventory/print', [InventoryPrintController::class, 'generate'])
    ->name('inventory.print');



// Sarafi Route

Route::get('/sarafi/home', function () {
    return view('Sarafi.components.dashboard');
})->name('sarafi.home');

Route::get('/sarafi', [CustomController::class, 'showLoginForm'])->name('sarafi.login.form');

Route::post('/sarafi/login', [CustomController::class, 'login'])->name('sarafi.login');

Route::post('/sarafi/logout', [CustomController::class, 'logout'])->name('sarafi.logout');



use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Session;

// Route::get('/set-locale/{locale}', function ($locale) {
//     $availableLocales = ['fa', 'ps', 'en'];

//     if (in_array($locale, $availableLocales)) {
//         Session::put('locale', $locale);
//         Cookie::queue('locale', $locale, 60 * 24 * 30); // 30 روز
//     }

//     return redirect()->back();
// })->name('set-locale');

// Route::prefix('sarafi')->group(function () {
//     Route::get('/', fn () => 'صفحه اصلی صرافی');
//     Route::get('/home', fn () => 'خانه صرافی');
// });
