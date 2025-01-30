<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LivewireController;
use App\Http\Controllers\MenuApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExpanseTableReportController;
use App\Http\Controllers\Exports\PurchaseTableReportController;
use App\Http\Controllers\Exports\ReportSalesIncentive;
use App\Http\Controllers\Exports\ReportSalesIncentiveXlsx;
use App\Http\Controllers\Exports\SalesTableReportController;
use App\Http\Controllers\PayTableReportController;
use App\Http\Controllers\ReceiveTableReportController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Expanse\ExpanseCreateManager;
use App\Http\Livewire\Expanse\ExpanseManager;
use App\Http\Livewire\Expanse\ExpanseViewManager;
use App\Http\Livewire\Expanse\ExpanseViewPrintManager;
use App\Http\Livewire\Expanse\PostPurchaseManager;
use App\Http\Livewire\Expanse\PostSalesManager;
use App\Http\Livewire\Invoice\InvoiceCreateManager;
use App\Http\Livewire\Invoice\InvoiceNonCreateManager;
use App\Http\Livewire\Invoice\InvoiceNonViewManager;
use App\Http\Livewire\Invoice\InvoiceNonViewPrintManager;
use App\Http\Livewire\Invoice\InvoiceViewManager;
use App\Http\Livewire\Invoice\InvoiceViewPrintManager;
use App\Http\Livewire\Masters\AccountCreateManager;
use App\Http\Livewire\Masters\AccountManager;
use App\Http\Livewire\Masters\CategoryAccountManager;
use App\Http\Livewire\Masters\ConfigurationManager;
use App\Http\Livewire\Masters\CustomersCreateManager;
use App\Http\Livewire\Masters\CustomersManager;
use App\Http\Livewire\Masters\PaymentMethodsCreateManager;
use App\Http\Livewire\Masters\PaymentMethodsManager;
use App\Http\Livewire\Masters\ProductsManager;
use App\Http\Livewire\Masters\ProfileCompanyManager;
use App\Http\Livewire\Masters\RolesCreateManager;
use App\Http\Livewire\Masters\RolesManager as MastersRolesManager;
use App\Http\Livewire\Masters\SuppliersCreateManager;
use App\Http\Livewire\Masters\SuppliersManager;
use App\Http\Livewire\Masters\UserCreateManager;
use App\Http\Livewire\MenuManager\MenuManager;
use App\Http\Livewire\Pengaturan\ProfileManager;
use App\Http\Livewire\Pengaturan\UsersManager;
use App\Http\Livewire\Pengaturan\RolesManager;
use App\Http\Livewire\Masters\UserManager;
use App\Http\Livewire\Payment\PayPaymentManager;
use App\Http\Livewire\Payment\PayPaymentViewManager;
use App\Http\Livewire\Payment\ReceivePaymentManager;
use App\Http\Livewire\Payment\ReceivePaymentViewManager;
use App\Http\Livewire\Purchase\PurchaseCreateManager;
use App\Http\Livewire\Purchase\PurchaseManager;
use App\Http\Livewire\Purchase\PurchaseNonCreateManager;
use App\Http\Livewire\Purchase\PurchaseNonManager;
use App\Http\Livewire\Purchase\PurchaseNonViewManager;
use App\Http\Livewire\Purchase\PurchaseNonViewPrintManager;
use App\Http\Livewire\Purchase\PurchaseViewManager;
use App\Http\Livewire\Purchase\PurchaseViewPrintManager;
use App\Http\Livewire\Reports\DayBookReportManager;
use App\Http\Livewire\Reports\ExpanseReportManager;
use App\Http\Livewire\Reports\PayReportManager;
use App\Http\Livewire\Reports\ProfitLossReportManager;
use App\Http\Livewire\Reports\PurchaseReportManager;
use App\Http\Livewire\Reports\ReceiveReportManager;
use App\Http\Livewire\Reports\SalesReportManager;
use App\Http\Livewire\Reports\SalesIncentiveManager;
use App\Http\Livewire\Sales\SalesCreateManager;
use App\Http\Livewire\Sales\SalesManager;
use App\Http\Livewire\Sales\SalesNonCreateManager;
use App\Http\Livewire\Sales\SalesNonManager;
use App\Http\Livewire\Sales\SalesNonViewManager;
use App\Http\Livewire\Sales\SalesNonViewPrintManager;
use App\Http\Livewire\Sales\SalesViewManager;
use App\Http\Livewire\Sales\SalesViewPrintManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/livewire', [LivewireController::class, 'index'])->name('livewire.index');
    Route::prefix('/admin')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('admin.index');

        // system
        Route::get('/user', [UserController::class, 'index'])->name('system.user');
        Route::get('/menu-manager', MenuManager::class)->name('system.menu-manager');
    });

    Route::get('/change_password', [ChangePasswordController::class, 'index'])->name('change-password');

    //Pengaturan
    Route::prefix('/masters')->group(function () {
        Route::prefix('/users')->group(function () {
            Route::get('/', UserManager::class)->name('masters.users');
            Route::get('/create', UserCreateManager::class)->name('masters.users-create');
        });

        Route::prefix('/roles')->group(function () {
            Route::get('/', MastersRolesManager::class)->name('masters.roles');
            Route::get('/create', RolesCreateManager::class)->name('masters.roles-create');
        });

        Route::get('/profile-company', ProfileCompanyManager::class)->name('masters.profile-company');
        Route::get('/products', ProductsManager::class)->name('masters.products');
        Route::get('/category-accounts', CategoryAccountManager::class)->name('masters.category-accounts');
        Route::get('/configuration', ConfigurationManager::class)->name('masters.configiration');

        Route::prefix('/accounts')->group(function () {
            Route::get('/', AccountManager::class)->name('masters.accounts');
            Route::get('/create', AccountCreateManager::class)->name('masters.accounts-create');
        });

        Route::prefix('/suppliers')->group(function () {
            Route::get('/', SuppliersManager::class)->name('masters.suppliers');
            Route::get('/create', SuppliersCreateManager::class)->name('masters.suppliers-create');
        });

        Route::prefix('/customers')->group(function () {
            Route::get('/', CustomersManager::class)->name('masters.customers');
            Route::get('/create', CustomersCreateManager::class)->name('masters.customers-create');
        });

        Route::prefix('/payment-methods')->group(function () {
            Route::get('/', PaymentMethodsManager::class)->name('masters.payment-methods');
            Route::get('/create', PaymentMethodsCreateManager::class)->name('masters.payment-methods-create');
        });
    });

    //Purchase
    Route::prefix('/purchase')->group(function () {
        Route::get('/', PurchaseManager::class)->name('purchase.index');
        Route::get('/create', PurchaseCreateManager::class)->name('purchase.create');
        Route::get('/view/{id}', PurchaseViewManager::class)->name('purchase.view');
        Route::get('/view/print/{id}', [PurchaseViewPrintManager::class, 'index'])->name('purchase.view.print');

        Route::prefix('/non-tax')->group(function () {
            Route::get('/', PurchaseNonManager::class)->name('purchase.non.index');
            Route::get('/create', PurchaseNonCreateManager::class)->name('purchase.non.create');
            Route::get('/view/{id}', PurchaseNonViewManager::class)->name('purchase.non.view');
            Route::get('/view/print/{id}', [PurchaseNonViewPrintManager::class, 'index'])->name('purchase.non.view.print');
        });
    });

    //Sales
    Route::prefix('/sales')->group(function () {
        Route::get('/', SalesManager::class)->name('sales.index');
        Route::get('/create', SalesCreateManager::class)->name('sales.create');
        Route::get('/view/{id}', SalesViewManager::class)->name('sales.view');
        Route::get('/view/print/{id}', [SalesViewPrintManager::class, 'index'])->name('sales.view.print');
        Route::get('/invoice/create/{so}', InvoiceCreateManager::class)->name('sales.invoice.create');
        Route::get('/invoice/view/{id}', InvoiceViewManager::class)->name('sales.invoice.view');
        Route::get('/invoice/view/print/{id}', [InvoiceViewPrintManager::class, 'index'])->name('sales.invoice.view.print');

        Route::prefix('/non-tax')->group(function () {
            Route::get('/', SalesNonManager::class)->name('sales.non.index');
            Route::get('/create', SalesNonCreateManager::class)->name('sales.non.create');
            Route::get('/view/{id}', SalesNonViewManager::class)->name('sales.non.view');
            Route::get('/view/print/{id}', [SalesNonViewPrintManager::class, 'index'])->name('sales.non.view.print');
            Route::get('/invoice/create/{so}', InvoiceNonCreateManager::class)->name('sales.non.invoice.create');
            Route::get('/invoice/view/{id}', InvoiceNonViewManager::class)->name('sales.non.invoice.view');
            Route::get('/invoice/view/print/{id}', [InvoiceNonViewPrintManager::class, 'index'])->name('sales.non.invoice.view.print');
        });
    });

    //Expanse
    Route::prefix('/expanse')->group(function () {
        Route::get('/', ExpanseManager::class)->name('expanse.index');
        Route::get('/create', ExpanseCreateManager::class)->name('expanse.create');
        Route::get('/view/{id}', ExpanseViewManager::class)->name('expanse.view');
        Route::get('/view/print/{id}', [ExpanseViewPrintManager::class, 'index'])->name('expanse.view.print');
        Route::prefix('/post')->group(function () {
            Route::get('/purchase', PostPurchaseManager::class)->name('expanse.post.purchase');
            Route::get('/sales', PostSalesManager::class)->name('expanse.post.sales');
        });
    });

    //Pay Payment
    Route::prefix('/pay')->group(function () {
        Route::get('/', PayPaymentManager::class)->name('pay.index');
        Route::get('/view/{id}/{type}', PayPaymentViewManager::class)->name('pay.view');
    });

    //Receive Payment
    Route::prefix('/receive')->group(function () {
        Route::get('/', ReceivePaymentManager::class)->name('receive.index');
        Route::get('/view/{id}/{type}', ReceivePaymentViewManager::class)->name('receive.view');
    });

    //Reports
    Route::prefix('/reports')->group(function () {
        Route::get('/sales', SalesReportManager::class)->name('reports.sales');
        Route::get('/purchase', PurchaseReportManager::class)->name('reports.purchase');
        Route::get('/expanse', ExpanseReportManager::class)->name('reports.expanse');
        Route::get('/profit-loss', ProfitLossReportManager::class)->name('reports.profit-loss');
        Route::get('/day-books', DayBookReportManager::class)->name('reports.day-books');
        Route::get('/receive', ReceiveReportManager::class)->name('reports.receive');
        Route::get('/pay', PayReportManager::class)->name('reports.pay');
        Route::get('/sales/incentive', SalesIncentiveManager::class)->name('reports.sales.incentive');
    });

    //Pengaturan
    Route::prefix('/pengaturan')->group(function () {
        Route::get('/profile', [ProfileManager::class, 'index'])->name('pengaturan.profile');
        Route::get('/user', UsersManager::class)->name('pengaturan.users');
        Route::get('/roles', RolesManager::class)->name('pengaturan.roles');
    });

    Route::prefix('/print')->group(function () {
        Route::get('/sales', [SalesTableReportController::class, 'index'])->name('print.sales');
        Route::get('/purchase', [PurchaseTableReportController::class, 'index'])->name('print.purchase');
        Route::get('/reports/sales/incentive', [ReportSalesIncentive::class, 'index'])->name('print.reports.sales.incentive');
    });

    Route::prefix('/export')->group(function () {
        Route::prefix('/xlsx')->group(function () {
            Route::get('/reports/sales/incentive', [ReportSalesIncentiveXlsx::class, 'index'])->name('export.xlsx.reports.sales.incentive');
            Route::get('/pay', [PayTableReportController::class, 'index'])->name('print.pay');
            Route::get('/receive', [ReceiveTableReportController::class, 'index'])->name('print.receive');
            Route::get('/expanse', [ExpanseTableReportController::class, 'index'])->name('print.expanse');
        });
    });

    Route::post('/menu-save-order', [MenuApiController::class, 'save_order']);
    Route::post('/menu-save-parent', [MenuApiController::class, 'save_parent']);
});
