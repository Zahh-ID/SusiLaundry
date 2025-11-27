<?php

use App\Livewire\Actions\Logout;
use App\Http\Controllers\Admin\OrderExportController;
use App\Http\Controllers\Admin\OrderPrintController;
use App\Livewire\Admin\Customer\Index as AdminCustomerIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Order\Create as AdminOrderCreate;
use App\Livewire\Admin\Order\Edit as AdminOrderEdit;
use App\Livewire\Admin\Order\Index as AdminOrderIndex;
use App\Livewire\Admin\Package\Create as AdminPackageCreate;
use App\Livewire\Admin\Package\Edit as AdminPackageEdit;
use App\Livewire\Admin\Package\Index as AdminPackageIndex;
use App\Livewire\Admin\Report\Index as AdminReportIndex;
use App\Livewire\CreateOrder;
use App\Livewire\ShowPackages;
use App\Livewire\TrackOrder;
use App\Models\Package;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $packages = Package::orderBy('price_per_kg')->take(3)->get();
    $completedOrders = \App\Models\Order::where('status', 'completed')
        ->latest()
        ->take(3)
        ->get();

    return view('landing', compact('packages', 'completedOrders'));
})->name('landing');

Route::get('/paket', ShowPackages::class)->name('packages.index');
Route::get('/order', CreateOrder::class)->name('order.create');
Route::get('/tracking', TrackOrder::class)->name('tracking');
Route::view('/promo', 'pages.promo')->name('promo');
Route::view('/tentang-kami', 'pages.about')->name('about');
Route::view('/kontak', 'pages.contact')->name('contact');
Route::get('/order/success/{code}', function (string $code) {
    $order = \App\Models\Order::with(['customer', 'package', 'payments' => fn($query) => $query->latest()])->where('order_code', $code)->firstOrFail();

    return view('order-success', compact('order'));
})->name('order.success');

Route::middleware(['auth', 'verified', 'prevent-back-history'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/orders', AdminOrderIndex::class)->name('orders.index');
        Route::get('/orders/create', AdminOrderCreate::class)->name('orders.create');
        Route::get('/orders/{order}/edit', AdminOrderEdit::class)->name('orders.edit');
        Route::get('/orders/{order}/print', OrderPrintController::class)->name('orders.print');
        Route::get('/packages', AdminPackageIndex::class)->name('packages.index');
        Route::get('/packages/create', AdminPackageCreate::class)->name('packages.create');
        Route::get('/packages/{package}/edit', AdminPackageEdit::class)->name('packages.edit');
        Route::get('/customers', AdminCustomerIndex::class)->name('customers.index');
        Route::get('/reports', AdminReportIndex::class)->name('reports.index');
        Route::get('/reports/orders/export', OrderExportController::class)->name('reports.orders.export');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('logout', function (Logout $logout) {
    $logout();

    return redirect('/');
})->middleware('auth')->name('logout');

require __DIR__ . '/auth.php';
