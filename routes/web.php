<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use app\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Auth;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {

        if (Auth::user()->role_id == 1) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.assessments');
        }
    }
    return app(App\Http\Controllers\PageController::class)->index();
})->name('home');

Route::get('/login', [PageController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [PageController::class, 'registerPage'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [PageController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/profile', [PageController::class, 'adminProfile'])->name('admin.profile');
    Route::patch('/profile/update', [PageController::class, 'updateAdminProfile'])->name('admin.profile.update');
    Route::get('/session/{session}', [SessionController::class, 'show'])->name('session.show');
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('admin.feedback.store');
    Route::post('/session/{sessionId}/store-something', [PageController::class, 'storeSomething'])->name('session.store_something');
    Route::get('/session/{id}/detail', [PageController::class, 'showSessionDetail'])->name('session.detail');
    Route::get('/admin/dashboard', [PageController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [UserController::class, 'index'])->name('admin.dashboard');
    Route::get('/user/progress/{id}', [PageController::class, 'showUserProgress'])->name('user.progress.show');

    Route::get('/assessments', [PageController::class, 'assessmentManagement'])->name('admin.assessments');
    Route::get('/assessments/create', [PageController::class, 'createAssessment'])->name('admin.assessments.create');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('admin.assessments.store');
    Route::get('/assessments/{assessment}/edit', [PageController::class, 'editAssessment'])->name('admin.assessments.edit');
    Route::put('/assessments/{assessment}', [AssessmentController::class, 'update'])->name('admin.assessments.update');
    Route::delete('/assessments/{assessment}', [AssessmentController::class, 'destroy'])->name('admin.assessments.destroy');

    Route::get('/assessments/{assessment}/questions', [PageController::class, 'manageQuestions'])->name('admin.questions');
    Route::post('/questions', [AssessmentController::class, 'storeQuestion'])->name('admin.questions.store');
    Route::post('/admin/questions/multi-store', [AssessmentController::class, 'multiStore'])->name('admin.questions.multi_store');
    Route::put('/questions/{question}', [AssessmentController::class, 'updateQuestion'])->name('admin.questions.update');
    Route::delete('/questions/{question}', [AssessmentController::class, 'destroyQuestion'])->name('admin.questions.destroy');
});

// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/assessments', [PageController::class, 'userAssessments'])->name('user.assessments');
    Route::get('/assessments/{assessment}', [PageController::class, 'takeAssessment'])->name('user.assessments.take');
    Route::post('/assessments/{assessment}/submit', [UserController::class, 'submitAssessment'])->name('user.assessments.submit');
    Route::get('/profile', [PageController::class, 'userProfile'])->name('user.profile');
    Route::patch('/profile/update', [PageController::class, 'updateUserProfile'])->name('user.profile.update');
    Route::get('/gamifikasi', [UserController::class, 'userAssessments'])->name('user.gamifikasi')->middleware('auth');
    Route::get('/user/progress', [PageController::class, 'userProgress'])->name('user.progress');
    // Route::get('/user/assessment-history', [UserController::class, 'myAssessmentHistory'])->name('user.assessment.history');

});


