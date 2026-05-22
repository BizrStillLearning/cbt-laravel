<?php

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'guru') {
        return Inertia::render('Dashboard', [
            'role' => 'guru',
            'stats' => [
                'total_ujian' => Exam::count(),
                'total_murid' => \App\Models\User::where('role', 'murid')->count(),
                'total_mengerjakan' => ExamAttempt::count(),
            ]
        ]);
    } else {
        $now = now();
        return Inertia::render('Dashboard', [
            'role' => 'murid',
            'exams' => Exam::where('is_published', true)
                ->where(function($query) use ($now) {
                    $query->whereNull('start_time')->orWhere('start_time', '<=', $now);
                })
                ->where(function($query) use ($now) {
                    $query->whereNull('end_time')->orWhere('end_time', '>=', $now);
                })
                ->latest()->get(),
            'my_attempts' => ExamAttempt::where('user_id', $user->id)->with('exam')->latest()->get()
        ]);
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:guru'])->group(function () {
    // Manajemen Ujian
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');

    // Manajemen Soal
    Route::get('/exams/{exam}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/exams/{exam}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/exams/{exam}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/exams/{exam}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/exams/{exam}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Manajemen Nilai
    Route::get('/exams/{exam}/results', [GradingController::class, 'index'])->name('grading.index');
    Route::get('/exams/{exam}/export', [GradingController::class, 'export'])->name('grading.export');
    Route::get('/attempts/{attempt}/grade', [GradingController::class, 'show'])->name('grading.show');
    Route::post('/attempts/{attempt}/grade', [GradingController::class, 'update'])->name('grading.update');
});

Route::middleware(['auth', 'role:murid'])->group(function () {
    Route::get('/take-exam/{exam}', [StudentExamController::class, 'lobby'])->name('student.exam.lobby');
    Route::get('/take-exam/{exam}/start', [StudentExamController::class, 'start'])->name('student.exam.start');
    Route::post('/take-exam/{exam}/submit', [StudentExamController::class, 'submit'])->name('student.exam.submit');
});

require __DIR__.'/auth.php';
