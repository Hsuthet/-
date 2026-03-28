<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestApprovalController;
use App\Http\Controllers\WorkerTaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class,'index'])
        ->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

        Route::get('/notifications/mark-as-read', function () {
    auth::user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.markAsRead');

    // Status Update
    Route::patch('/business-requests/{business_request}/status',
        [BusinessRequestController::class, 'updateStatus']
    )->name('business-requests.updateStatus');

    // Confirmation & Complete
    Route::post('/business-requests/confirm',
        [BusinessRequestController::class, 'confirm']
    )->name('business-requests.confirm');

    Route::post('/business-requests/complete',
        [BusinessRequestController::class, 'complete']
    )->name('business-requests.complete');

    Route::post('/file/remove', [BusinessRequestController::class, 'remove'])->name('file.remove');
    // Business Requests Resource
   // Business Requests Resource (This already creates 'business-requests.show')
    Route::resource('business-requests', BusinessRequestController::class);

}); // End of auth middleware group

Route::get('/business-requests/{request}/approve', [RequestApprovalController::class, 'approveForm'])->name('business-requests.approve');
Route::post('/business-requests/{businessRequest}/assign', [RequestApprovalController::class, 'assign'])->name('business-requests.assign');

// FIX 1: Renamed 'business-requests.show' to 'business-requests.display' to avoid conflict with the resource above
Route::get('/business-requests/user/{id}', [BusinessRequestController::class, 'showUser'])->name('business-requests.display');

Route::get('/requests', [BusinessRequestController::class, 'myRequests'])->name('business-requests.requests');

Route::get('/my-tasks', [WorkerTaskController::class, 'index'])->name('business-requests.my_tasks');

// FIX 2: Removed the duplicate 'tasks.update-status' route (kept only one)
Route::patch('/tasks/{businessRequest}/status', [WorkerTaskController::class, 'updateStatus'])->name('tasks.update-status');

require __DIR__.'/auth.php';