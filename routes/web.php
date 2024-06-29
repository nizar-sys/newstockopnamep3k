<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ApprovalRecordsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChecklistRecordController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\TreeviewController;

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

# ------ Unauthenticated routes ------ #
Route::get('/', [RouteController::class, 'home']);
Route::get('/scan-checklist-records', [RouteController::class, 'checklistRecords'])->name('landing.checklist');
require __DIR__.'/auth.php';


# ------ Authenticated routes ------ #
Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [RouteController::class, 'dashboard'])->name('home'); # dashboard

    Route::prefix('profile')->group(function(){
        Route::get('/', [ProfileController::class, 'myProfile'])->name('profile');
        Route::put('/change-ava', [ProfileController::class, 'changeFotoProfile'])->name('change-ava');
        Route::put('/change-profile', [ProfileController::class, 'changeProfile'])->name('change-profile');
    }); # profile group

    Route::resource('users', UserController::class);

    Route::put('/rooms/{room}/update-items', [RoomController::class, 'updateItems'])->name('rooms.item.update');
    Route::resource('rooms', RoomController::class);

    Route::prefix('/checklist-records')->group(function(){
        Route::get('/print', [ChecklistRecordController::class, 'printRecords'])->name('print-records');
        Route::get('/export', [ChecklistRecordController::class, 'exportRecords'])->name('checklist-records.export');
        Route::post('/items', [ChecklistRecordController::class, 'storeItem'])->name('checklist-records.item.store');
        Route::put('/items/{item}', [ChecklistRecordController::class, 'updateItem'])->name('checklist-records.item.update');
        Route::delete('/items/{item}/destroy', [ChecklistRecordController::class, 'destroyItem'])->name('checklist-records.item.destroy');
    });
    Route::resource('checklist-records', ChecklistRecordController::class);

    Route::prefix('/approval-records')->group(function(){
        Route::post('/approve', [ApprovalRecordsController::class, 'approveChecklist'])->name('approval-records.checklist.store');
    });
    Route::resource('approval-records', ApprovalRecordsController::class);
    Route::resource('activity-logs', ActivityLogController::class);
});
