<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CriteriaComparisonController;
use App\Http\Controllers\SupplierAssessmentController;
use App\Http\Controllers\SupplierTrackRecordController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Criteria Management
    Route::resource('criteria', CriteriaController::class);

    // Supplier Management
    Route::resource('suppliers', SupplierController::class);

    // AHP Criteria Comparison
    Route::prefix('criteria-comparisons')->name('criteria-comparisons.')->group(function () {
        Route::get('/', [CriteriaComparisonController::class, 'index'])->name('index');
        Route::get('/create', [CriteriaComparisonController::class, 'create'])->name('create');
        Route::post('/', [CriteriaComparisonController::class, 'store'])->name('store');
        Route::post('/calculate', [CriteriaComparisonController::class, 'calculate'])->name('calculate');
        Route::get('/result', [CriteriaComparisonController::class, 'result'])->name('result');
        Route::delete('/reset', [CriteriaComparisonController::class, 'reset'])->name('reset');
        Route::delete('/{criteriaComparison}', [CriteriaComparisonController::class, 'destroy'])->name('destroy');
    });

    // Supplier Track Records
    // 🔥 FIXED: Use explicit URL segments to avoid route conflicts
    Route::prefix('track-records')->name('track-records.')->group(function () {
        Route::get('/', [SupplierTrackRecordController::class, 'index'])->name('index');

        // Supplier-specific routes (use 'supplier' prefix for clarity)
        Route::get('/supplier/{supplier}', [SupplierTrackRecordController::class, 'show'])->name('show');
        Route::get('/supplier/{supplier}/edit', [SupplierTrackRecordController::class, 'edit'])->name('edit');
        Route::put('/supplier/{supplier}/update', [SupplierTrackRecordController::class, 'update'])->name('update');
        Route::post('/supplier/{supplier}/reset', [SupplierTrackRecordController::class, 'reset'])->name('reset');
        Route::post('/supplier/{supplier}/initialize', [SupplierTrackRecordController::class, 'initialize'])->name('initialize');
        Route::delete('/supplier/{supplier}/clear-activities', [SupplierTrackRecordController::class, 'clearActivities'])->name('clear-activities');

        // Track record specific routes
        Route::delete('/record/{trackRecord}', [SupplierTrackRecordController::class, 'destroy'])->name('destroy');
    });

    // Supplier Assessment
    Route::prefix('supplier-assessments')->name('supplier-assessments.')->group(function () {
        Route::get('/', [SupplierAssessmentController::class, 'index'])->name('index');
        Route::get('/create', [SupplierAssessmentController::class, 'create'])->name('create');
        Route::post('/', [SupplierAssessmentController::class, 'store'])->name('store');
        Route::get('/ranking', [SupplierAssessmentController::class, 'ranking'])->name('ranking');
        Route::get('/export-pdf', [SupplierAssessmentController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export-excel', [SupplierAssessmentController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export-detail-pdf', [SupplierAssessmentController::class, 'exportDetailPdf'])->name('export.detail');
        Route::delete('/{supplierAssessment}', [SupplierAssessmentController::class, 'destroy'])->name('destroy');
        Route::post('/reset', [SupplierAssessmentController::class, 'reset'])->name('reset');
    });

    // USER MANAGEMENT - ADMIN ONLY (FIXED ORDER!)
    Route::middleware('admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::patch('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::patch('/{user}/promote', [UserManagementController::class, 'promoteToAdmin'])->name('promote');
        Route::patch('/{user}/demote', [UserManagementController::class, 'demoteToUser'])->name('demote');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

    Route::middleware(['auth'])->group(function () {
        Route::patch('suppliers/{supplier}/toggle-active', [SupplierController::class, 'toggleActive'])
            ->name('suppliers.toggle-active');

        Route::patch('criteria/{criterion}/toggle-active', [CriteriaController::class, 'toggleActive'])
            ->name('criteria.toggle-active');
    });
});

require __DIR__ . '/auth.php';
