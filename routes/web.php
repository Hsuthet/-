<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestApprovalController;
use App\Http\Controllers\WorkerTaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskExportController;
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

// Admin Only
// Admin Only
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.') 
    ->group(function () {
        Route::resource('users', UserController::class);
    });

// All Logged-in Users
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Notifications
    Route::get('/notifications/mark-as-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAsRead');

    /*
    |--------------------------------------------------------------------------
    | Business Requests
    |--------------------------------------------------------------------------
    */
 Route::patch('/business-requests/{business_request}/status',
        [BusinessRequestController::class, 'updateStatus']
    )->name('business-requests.updateStatus');
    

    Route::prefix('business-requests')->name('business-requests.')->group(function () {

    Route::get('/list/my-requests', [BusinessRequestController::class, 'myRequests'])
        ->name('requests');

    Route::get('/list/user/{businessRequest}', [BusinessRequestController::class, 'show'])
        ->name('display');

    Route::post('/confirm', [BusinessRequestController::class, 'confirm'])->name('confirm');
    Route::post('/complete', [BusinessRequestController::class, 'complete'])->name('complete');
    Route::post('/file/remove', [BusinessRequestController::class, 'remove'])->name('file.remove');

    // FIXED
    Route::get('/{businessRequest}/approve', [RequestApprovalController::class, 'approveForm'])
        ->name('approve');

    Route::post('/{businessRequest}/assign', [RequestApprovalController::class, 'assign'])
        ->name('assign');
});

    // ✅ Resource LAST (important!)
    Route::resource('business-requests', BusinessRequestController::class);

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */
    Route::get('/my-tasks', [WorkerTaskController::class, 'index'])->name('business-requests.my_tasks');
    Route::patch('/tasks/{businessRequest}/status', [WorkerTaskController::class, 'updateStatus'])->name('tasks.update-status');

});


Route::get('/tasks/export/excel', [TaskExportController::class, 'exportExcel'])
    ->name('tasks.export.excel');
require __DIR__.'/auth.php';