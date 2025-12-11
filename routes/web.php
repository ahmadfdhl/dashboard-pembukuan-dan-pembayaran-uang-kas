<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teacher\ClassController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\StudentController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

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
});

Route::middleware(['auth', 'verified', 'isTeacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Kelas Routes
    Route::resource('/classes', ClassController::class);
    // Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    // Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
    // Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    // Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
    // Route::get('/classes/{class}/edit', [ClassController::class, 'edit'])->name('classes.edit');
    // Route::put('/classes/{class}', [ClassController::class, 'update'])->name('classes.update');
    // Route::delete('/classes/{class}/destroy', [ClassController::class, 'destroy'])->name('classes.destroy');
    
    // Siswa Routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::post('/students/{student}/toggle-treasurer', [StudentController::class, 'toggleTreasurer'])->name('students.toggle-treasurer');
});