<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\GradeController as AdminGradeController;
use App\Http\Controllers\Admin\JournalController as AdminJournalController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:siswa')->prefix('siswa')->name('student.')->group(function (): void {
        Route::get('/absensi', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/absensi', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/jurnal', [JournalController::class, 'index'])->name('journals.index');
        Route::post('/jurnal', [JournalController::class, 'store'])->name('journals.store');
        Route::put('/profil/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/siswa', [AdminStudentController::class, 'index'])->name('students.index');
        Route::get('/siswa/tambah', [AdminStudentController::class, 'create'])->name('students.create');
        Route::post('/siswa', [AdminStudentController::class, 'store'])->name('students.store');
        Route::get('/siswa/{student}/ubah', [AdminStudentController::class, 'edit'])->name('students.edit');
        Route::put('/siswa/{student}', [AdminStudentController::class, 'update'])->name('students.update');
        Route::delete('/siswa/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
        Route::get('/absensi', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/jurnal', [AdminJournalController::class, 'index'])->name('journals.index');
        Route::get('/nilai', [AdminGradeController::class, 'index'])->name('grades.index');
        Route::get('/nilai/{grade}/ubah', [AdminGradeController::class, 'edit'])->name('grades.edit');
        Route::put('/nilai/{grade}', [AdminGradeController::class, 'update'])->name('grades.update');
    });
});
