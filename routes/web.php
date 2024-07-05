<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\FundraiserController;
use App\Http\Controllers\FundraisingController;
use App\Http\Controllers\FundraisingPhasesController;
use App\Http\Controllers\FundraisingWithdrawalController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //Without role
        Route::post('/fundraiser/apply', [DashboardController::class, 'apply_fundraiser'])->name('fundraiser.apply');  
        Route::get('/my-withdrawals', [DashboardController::class, 'my_withdrawals'])->name('my-withdrawals');
        Route::get('/my-withdrawals/details/{fundraisingWithdrawal}', [DashboardController::class, 'my_withdrawals_detail'])->name('my-withdrawals.detail');

    // Owner 
        Route::prefix('admin')->name('.admin')->group(function(){ 
        Route::resource('categories', CategoryController::class)
        ->middleware('role:owner');

        Route::resource('donaturs', DonaturController::class)
        ->middleware('role:owner');

        Route::resource('fundraisers', FundraiserController::class)
        ->middleware('role:owner')->except('index');

        Route::post('/fundraisings/active/{fundraising}', [FundraisingController::class, 'activate_fundraising'])
        ->middleware('role:owner')
        ->name('fundraising.withdrawals.activate_fundraising');

        
    // Owner and fundraiser
        Route::resource('fundraising_withdrawals', FundraisingWithdrawalController::class)
        ->middleware('role:owner|fundraiser');

        Route::resource('fundraising_phases', FundraisingPhasesController::class)
        ->middleware('role:owner|fundraiser');

        Route::resource('fundraisings', FundraisingController::class)
        ->middleware('role:owner:fundraiser');


    // Fundraiser
        Route::post('fundraising_withdrawals/request/{fundraising}', [FundraisingWithdrawalController::class])
        ->middleware('role:fundraiser')
        ->name('fundraising_withdrawals.store');

        Route::post('fundraising_phases/update/{fundraising}', [FundraisingPhasesController::class])
        ->middleware('role:fundraiser')
        ->name('fundraising_phases.store');
    });
});

require __DIR__.'/auth.php';
