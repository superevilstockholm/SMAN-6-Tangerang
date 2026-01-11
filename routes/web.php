<?php

use Illuminate\Support\Facades\Route;

// Auth Controller
use App\Http\Controllers\AuthController;

// Master Data Controllers
use App\Http\Controllers\MasterData\UserController;
use App\Http\Controllers\MasterData\NewsController;
use App\Http\Controllers\MasterData\VisionController;
use App\Http\Controllers\MasterData\MissionController;
use App\Http\Controllers\MasterData\TeacherController;
use App\Http\Controllers\MasterData\SchoolHistoryController;

// Settings Controllers
use App\Http\Controllers\Settings\ActivityLogController;

Route::get('/', function () {
    return view('pages.index');
});

// Optional Auth
Route::middleware(['optional.auth.sanctum.cookie'])->group(function () {
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});

// Protected
Route::middleware(['auth.sanctum.cookie', 'activity.log'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('/dashboard')->name('dashboard.')->group(function () {
        // Admin
        Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', function () {
                return view('pages.dashboard.admin.index', [
                    'meta' => ['sidebarItems' => adminSidebarItems()],
                ]);
            })->name('index');
            // Master Data
            Route::prefix('master-data')->name('master-data.')->middleware(['validate.pagination'])->group(function () {
                Route::resource('users', UserController::class)->parameters([
                    'users' => 'user'
                ]);
                Route::resource('school-histories', SchoolHistoryController::class)->parameters([
                    'school-histories' => 'schoolHistory'
                ]);
                Route::resource('visions', VisionController::class)->parameters([
                    'visions' => 'vision'
                ]);
                Route::resource('missions', MissionController::class)->parameters([
                    'missions' => 'mission'
                ]);
                Route::resource('teachers', TeacherController::class)->parameters([
                    'teachers' => 'teacher'
                ]);
                Route::resource('news', NewsController::class)->parameters([
                    'news' => 'news'
                ]);
            });
            Route::prefix('settings')->name('settings.')->middleware(['validate.pagination'])->group(function () {
                Route::resource('activity-logs', ActivityLogController::class)->parameters([
                    'activity-logs' => 'activityLog'
                ])->only(['index', 'show']);
            });
        });
        // Teacher
        Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
            Route::get('/', function () {
                return view('pages.dashboard.teacher.index', [
                    'meta' => ['sidebarItems' => adminSidebarItems()],
                ]);
            })->name('index');
            Route::prefix('master-data')->name('master-data.')->middleware(['validate.pagination'])->group(function () {
                Route::resource('news', NewsController::class)->parameters([
                    'news' => 'news'
                ]);
            });
        });
        // Student
        Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
            Route::get('/', function () {
                return view('pages.dashboard.student.index', [
                    'meta' => ['sidebarItems' => adminSidebarItems()],
                ]);
            })->name('index');
        });
    });
});
