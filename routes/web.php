<?php

use App\Filament\Import\Pages\SaleReportGeneral;
use App\Http\Controllers\AccountingPrintController;
use App\Http\Controllers\AmountController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContractPrintController;
use App\Http\Controllers\DepositLogPrintController;
use App\Http\Controllers\Gym\Auth\UserControllers;
use App\Http\Controllers\InventoryPrintController;
use App\Http\Controllers\Market\Auth\AuthUser;
use App\Http\Controllers\OutsideController;
use App\Http\Controllers\PrintBoothContract;
use App\Http\Controllers\PrintContract;
use App\Http\Controllers\printLoan;
use App\Http\Controllers\SalaryPrintController;
use App\Http\Controllers\Sarafi\Auth\CustomController;
use App\Http\Controllers\Sarafi\CustomerController;
use App\Http\Controllers\ShopkeeperPrintController;
use App\Http\Controllers\SignedImagePdfController;
use App\Http\Controllers\StaffContractPrintController;
use App\Http\Controllers\ToolsPanel\Auth\UserController;
use App\Http\Controllers\WarehousePrintController;
use App\Http\Controllers\WithdrawPrint;
use App\Http\Livewire\Sarafi\customers;
use App\Livewire\Sarafi\ConversionTransfer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;





 


// Sarafi

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

// routes/web.php
Route::get('/accounting/print/{id}', [AccountingPrintController::class, 'printView'])
    ->name('accounting.print.view');

    


// Import system route 

Route::get('/warehouse/print', [WarehousePrintController::class, 'generate'])
    ->name('warehouse.print');
    Route::get('/inventory/print', [InventoryPrintController::class, 'generate'])
    ->name('inventory.print');

    Route::get('/test-sale-report', function () {
    return app(SaleReportGeneral::class)->mount();
});


// Sarafi Route

Route::get('/sarafi', [CustomController::class, 'showLoginForm'])->name('sarafi.login.form');

Route::post('/sarafi/login', [CustomController::class, 'login'])->name('sarafi.login');

Route::post('/sarafi/logout', [CustomController::class, 'logout'])->name('sarafi.logout');

Route::get('/set-locale/{locale}', function ($locale) {
    $availableLocales = ['fa', 'ps', 'en'];

    if (in_array($locale, $availableLocales)) {
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 30); // 30 روز
    }

    return redirect()->back();
})->name('set-locale');




// Pages    


Route::get('/sarafi/home', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.dashboard');
})->name('sarafi.home');


// Route::get('/sarafi/user', function () {
//     if (!Auth::guard('sarafi')->check()) {
//         return redirect()->route('sarafi.login.form');
//     }
//     return view('Sarafi.components.user-management');
// })->name('sarafi.users');


// روت‌های معمولی Laravel (بدون Livewire)
Route::get('/sarafi/customer-table', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.customer_table');
})->name('sarafi.customer-table');


Route::get('/sarafi/customer-create', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    
    // دریافت customerId از query parameter
    $customerId = request('customerId');
    
    return view('Sarafi.components.customer-create', [
        'customerId' => $customerId
    ]);
})->name('sarafi.customer-create');


Route::get('/sarafi/users', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    
    // دریافت customerId از query parameter
    $customerId = request('customerId');
    
    return view('Sarafi.components.users', [
        'customerId' => $customerId
    ]);
})->name('sarafi.users');

Route::get('/sarafi/customer-transactions', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.transaction');
})->name('sarafi.transactions');


Route::get('/sarafi/customers', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.transaction');
})->name('sarafi.customers.create');



Route::get('/sarafi/transactions-reports', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.transactions-reports');
})->name('sarafi.transaction-reports');



Route::get('/sarafi/accounts-jornal', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.accountsjornal');
})->name('sarafi.accountsjornal');


Route::get('/sarafi/buy-sell-currency', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.buy-sell-currency');
})->name('sarafi.buy-sell-currency');



Route::get('/sarafi/conversion-transfer', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.conversion-transfer');
})->name('sarafi.conversion-transfer');


Route::get('/sarafi/conversion-in-account', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.conversion-in-account');
})->name('sarafi.conversion.in.account');

Route::get('/sarafi/account_to_account', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.account-to-account');
})->name('sarafi.account_to_account');


Route::get('/sarafi/exchange-rate', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.exchange-rate');
})->name('sarafi.exchange-rate');


Route::get('/sarafi/remittance', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.remittance');
})->name('sarafi.remittance');

Route::get('/sarafi/remittance-approval', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.remittance-approval');
})->name('sarafi.remittance-approval');


Route::get('/sarafi/trash-edit', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.trash-edit');
})->name('sarafi.trash-edit');


Route::get('/sarafi/account-reports', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.account-reports');
})->name('sarafi.account-reports');


Route::get('/sarafi/general-reports', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.general-reports');
})->name('sarafi.general-reports');


Route::get('/sarafi/revenue', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.revene');
})->name('sarafi.revenue');


Route::get('/sarafi/profit-rates', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.profit-rates');
})->name('sarafi.profit-rates');


// copy customer
Route::prefix('api')->middleware('auth:sarafi')->group(function () {
    Route::get('/search-customers', [CustomerController::class, 'searchCustomers'])
        ->name('api.search-customers');

    Route::post('/link-customer', [CustomerController::class, 'linkCustomerToMe'])
        ->name('api.link-customer');

    Route::get('/linked-customers', [CustomerController::class, 'getLinkedCustomers'])
        ->name('api.linked-customers');
});


Route::get('/sarafi/changersdeal', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.Changersdeal');
})->name('sarafi.changersdeal');



Route::get('/sarafi/changers_reciver', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.changer-recive');
})->name('sarafi.changer_recive');



Route::get('/sarafi/sarafi-reports', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.sarafi-reports');
})->name('sarafi.sarafi_reports');




Route::get('/sarafi/withdraws-from-bank', function () {
    if (!Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.login.form');
    }
    return view('Sarafi.components.withdrawbank');
})->name('sarafi.withdrawbank');




// Chat Routes
Route::middleware(['auth:sarafi'])->prefix('chat')->group(function () {
    Route::post('/send', [ChatController::class, 'sendMessage']);
    Route::get('/messages/{userId}', [ChatController::class, 'getMessages']);
    Route::get('/conversations', [ChatController::class, 'getConversations']);
    Route::get('/users', [ChatController::class, 'getChatUsers']);
    Route::get('/unread-count', [ChatController::class, 'getUnreadCount']);
    Route::post('/search', [ChatController::class, 'searchUsers']);
    Route::post('/mark-all-read', [ChatController::class, 'markAllAsRead']);
});























// ToolsPanel Route
Route::get('/tools', [UserController::class, 'showLoginForm'])->name('tools.login.form');

Route::post('/tools/login', [UserController::class, 'login'])->name('tools.login');

Route::post('/tools/logout', [UserController::class, 'logout'])->name('tools.logout');



Route::get('/set-locale/{locale}', function ($locale) {
    $availableLocales = ['fa', 'ps', 'en'];

    if (in_array($locale, $availableLocales)) {
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 30); // 30 روز
    }

    return redirect()->back();
})->name('set-locale');

// Pages    

Route::get('/tools/home', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.dashboard');
})->name('tools.home');


// user
Route::get('/tools/users', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    
    $customerId = request('customerId');
    
    return view('ToolsPanel.components.users', [
        'customerId' => $customerId
    ]);
})->name('tools.users');

// customer
Route::get('/tools/customer-create', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    
    $customerId = request('customerId');
    
    return view('ToolsPanel.components.customer-create', [
        'customerId' => $customerId
    ]);
})->name('tools.customer-create');




Route::get('/tools/customer-table', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.customer_table');
})->name('tools.customer-table');


Route::get('/tools/loans', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.loans');
})->name('tools.loans');




Route::get('/tools/staff', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.staff');
})->name('tools.staff');


Route::get('/tools/salary', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.salary');
})->name('tools.salary');




Route::get('/tools/withdrawal', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.withdrawals');
})->name('tools.withdrawals');



Route::get('/tools/customer-transactions', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.transaction');
})->name('tools.transactions');




Route::get('/tools/transactions-reports', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.transactions-reports');
})->name('tools.transaction-reports');





Route::get('/tools/buy-sell-currency', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.buy-sell-currency');
})->name('tools.buy-sell-currency');



Route::get('/tools/conversion-transfer', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.conversion-transfer');
})->name('tools.conversion-transfer');


Route::get('/tools/conversion-in-account', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.conversion-in-account');
})->name('tools.conversion.in.account');

Route::get('/tools/account_to_account', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.account-to-account');
})->name('tools.account_to_account');



Route::get('/tools/safes', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.safes');
})->name('tools.safes');


Route::get('/tools/shop_to_sarafi', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.shop-transactions');
})->name('tools.shop-transactions');

Route::get('/tools/shop_conversion', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.shop-conversion');
})->name('tools.shop-conversion');




Route::get('/tools/inventory', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.inventory');
})->name('tools.inventory');



Route::get('/tools/warehouse', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.warehouse');
})->name('tools.warehouse');




Route::get('/tools/sales', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.sales');
})->name('tools.sales');


Route::get('/tools/reports', function () {
    if (!Auth::guard('tools')->check()) {
        return redirect()->route('tools.login.form');
    }
    return view('ToolsPanel.components.general-report');
})->name('tools.reports');






// Gym routes

Route::get('/gym', [UserControllers::class, 'showLoginForm'])->name('gym.login.form');




// Gym routes

Route::get('/gym', [UserControllers::class, 'showLoginForm'])->name('gym.login.form');

Route::post('/gym/login', [UserControllers::class, 'login'])->name('gym.login');

Route::post('/gym/logout', [UserControllers::class, 'logout'])->name('gym.logout');



Route::get('/set-locale/{locale}', function ($locale) {
    $availableLocales = ['fa', 'ps', 'en'];

    if (in_array($locale, $availableLocales)) {
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 30); // 30 روز
    }

    return redirect()->back();
})->name('set-locale');

Route::get('/gym/home', function () {
    if (!Auth::guard('gyms')->check()) {
        return redirect()->route('gym.login.form');
    }
    return view('Gym.components.homepage');
})->name('gym.home');



Route::get('/gym/clubaccounting', function () {
    if (!Auth::guard('gyms')->check()) {
        return redirect()->route('gym.login.form');
    }
    return view('Gym.ClubAccounting.dashboard');
})->name('gym.clubaccounting');













// Updated Market

Route::get('/update', [AuthUser::class, 'showLoginForm'])->name('market.login.form');

Route::post('/update-market/login', [AuthUser::class, 'login'])->name('market.login');

Route::post('/updated-market/logout', [AuthUser::class, 'logout'])->name('market.logout');



Route::get('/set-locale/{locale}', function ($locale) {
    $availableLocales = ['fa', 'ps', 'en'];

    if (in_array($locale, $availableLocales)) {
        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 30); // 30 روز
    }

    return redirect()->back();
})->name('set-locale');

// Pages    

Route::get('/updated-market/home', function () {
    if (!Auth::guard('market')->check()) {
        return redirect()->route('market.login.form');
    }
    return view('Market.components.dashboard');
})->name('market.home');







