<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/auth.php';

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

// routes/web.php
Route::middleware(['auth', 'verified', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Classes Routes
    Route::get('/classes', [AdminClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [AdminClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [AdminClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{class}', [AdminClassController::class, 'show'])->name('classes.show');
    Route::get('/classes/{class}/edit', [AdminClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{class}', [AdminClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{class}', [AdminClassController::class, 'destroy'])->name('classes.destroy');
    Route::post('/classes/{class}/add-student', [AdminClassController::class, 'addStudent'])->name('classes.add-student');
    Route::delete('/classes/{class}/remove-student/{student}', [AdminClassController::class, 'removeStudent'])->name('classes.remove-student');
    
    // Students Routes
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [AdminStudentController::class, 'create'])->name('students.create');
    Route::post('/students', [AdminStudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [AdminStudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/students/{student}/toggle-active', [AdminStudentController::class, 'toggleActive'])->name('students.toggle-active');
    Route::post('/students/{student}/toggle-treasurer', [AdminStudentController::class, 'toggleTreasurer'])->name('students.toggle-treasurer');
    Route::put('/students/{student}/remove-from-class', [AdminStudentController::class, 'removeFromClass'])->name('students.remove-from-class');
    Route::post('/students/bulk-actions', [AdminStudentController::class, 'bulkActions'])->name('students.bulk-actions');
    Route::get('/students/export', [AdminStudentController::class, 'export'])->name('students.export');
});

Route::middleware(['auth', 'verified', 'isTeacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    // Kelas Routes
    Route::resource('/classes', TeacherClassController::class);


    // Student Routes
    Route::post('/students/{student}/toggle-active', [TeacherStudentController::class, 'toggleActive'])->name('students.toggle-active');
    Route::post('/students/{student}/toggle-treasurer', [TeacherStudentController::class, 'toggleTreasurer'])->name('students.toggle-treasurer');
    Route::put('/students/{student}/remove-from-class', [TeacherStudentController::class, 'removeFromClass'])->name('students.remove-from-class');
    Route::post('/students/bulk-actions', [TeacherStudentController::class, 'bulkActions'])->name('students.bulk-actions');
    Route::get('/students/export', [TeacherStudentController::class, 'export'])->name('students.export');
    Route::resource('/students', TeacherStudentController::class);
});
