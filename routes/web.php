<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\User\RequestController;
use App\Models\DetailTransaction;
use App\Models\OutgoingTransaction;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class, 'index']);
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// user
Route::middleware(['user'])->group(function () {
    Route::view('/home', 'user.index', ['link' => 'Home'])->name('user-index');

    Route::get('/request', [RequestController::class, 'index'])->name('user-request');
    Route::get('/request/cart', [RequestController::class, 'showCart'])->name('user-request-cart');
    Route::delete('/request/cart', [RequestController::class, 'removeFromCart'])->name('user-remove-cart');
    Route::post('/request/cart', [RequestController::class, 'store'])->name('user-request-store');
    Route::get('/request/{item:slug}', [RequestController::class, 'request'])->name('user-request-item');
    Route::post('/request/{slug}', [RequestController::class, 'addToCart'])->name('user-add-cart');

    Route::get('/history', function () {
        return view('user.history', [
            'link' => 'History',
            'outgoing_transactions' => OutgoingTransaction::with(['detailTransactions.itemDetail'])->where('user_id', session()->get('user_id'))->latest()->get(),
        ]);
    })->name('user-history');
});

// admin
Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin-index');
    Route::post('/dashboard/export', [DashboardController::class, 'export'])->name('admin-export');
    Route::get('/dashboard/{item:slug}', [DashboardController::class, 'show'])->name('admin-item-detail');
    Route::put('/dashboard/{slug}', [DashboardController::class, 'updateStatus'])->name('admin-item-detail-status');
    Route::delete('/dashboard', [DashboardController::class, 'delete'])->name('admin-item-delete');
    // Route::get('/dashboard/{item:slug}/edit', [DashboardController::class, 'edit'])->name('admin-item-edit');
    // Route::patch('/dashboard/{item:slug}', [DashboardController::class, 'update'])->name('admin-item-update');

    Route::get('/add-item', [ItemController::class, 'index'])->name('admin-item-add');
    Route::post('/add-item', [ItemController::class, 'storeItem'])->name('admin-item-store');
    Route::get('/download-template', [ItemController::class, 'downloadTemplate'])->name('admin-download-template');
    Route::post('/import', [ItemController::class, 'import'])->name('admin-import');

    Route::get('/category', [ItemController::class, 'category'])->name('admin-category');
    Route::post('/category', [ItemController::class, 'storeCategory'])->name('admin-store-category');
    Route::delete('/category', [ItemController::class, 'deleteCategory'])->name('admin-delete-category');

    Route::get('/accounts/admin', [AccountController::class, 'index'])->name('admin-accounts-admin');
    Route::put('/accounts/admin', [AccountController::class, 'update'])->name('admin-accounts-update');
    Route::get('/accounts/user', [AccountController::class, 'users'])->name('admin-accounts-user');
    Route::delete('/accounts/user', [AccountController::class, 'delete'])->name('admin-accounts-delete');
    Route::get('/accounts/{role}/create', [AccountController::class, 'create'])->name('admin-accounts-create');
    Route::post('/accounts/{role}/create', [AccountController::class, 'store'])->name('admin-accounts-store');

    Route::get('/transactions/in', [TransactionController::class, 'index'])->name('admin-incoming-transaction');
    Route::get('/transactions/out/pending', [TransactionController::class, 'pending'])->name('admin-outgoing-transaction-pending');
    Route::put('/transactions/out/pending/approve', [TransactionController::class, 'approve'])->name('admin-outgoing-transaction-pending-approve');
    Route::put('/transactions/out/pending/reject', [TransactionController::class, 'reject'])->name('admin-outgoing-transaction-pending-reject');
    Route::get('/transactions/out/approved', [TransactionController::class, 'approved'])->name('admin-outgoing-transaction-approved');
    Route::post('/transactions/out/approved/{action}', [TransactionController::class, 'scan'])->name('admin-outgoing-transaction-approved-scan');
    Route::get('/transactions/out/ongoing', [TransactionController::class, 'ongoing'])->name('admin-outgoing-transaction-ongoing');
    Route::post('/transactions/out/ongoing', [TransactionController::class, 'printInvoice'])->name('admin-outgoing-transaction-ongoing-invoice');
    Route::get('/transactions/out/completed', [TransactionController::class, 'completed'])->name('admin-outgoing-transaction-completed');
    Route::get('/transactions/out/rejected', [TransactionController::class, 'rejected'])->name('admin-outgoing-transaction-rejected');

    $outgoing_transaction = OutgoingTransaction::find(5);
    Route::view('/invoice', 'invoice', [
        'transaction' => $outgoing_transaction,
        'code' => 123456,
        // 'admin' => session('user_name')
    ]);
});
