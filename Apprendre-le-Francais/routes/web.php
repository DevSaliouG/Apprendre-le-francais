<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\ExerciseAdminController;
use App\Http\Controllers\Admin\LessonAdminController;
use App\Http\Controllers\Admin\LevelAdminController;
use App\Http\Controllers\Admin\QuestionAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfilUserController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LevelTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StreakController;
use App\Http\Controllers\UserController as ControllersUserController;
use App\Http\Controllers\UserProgressController;
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

/* 



require __DIR__.'/auth.php';

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

 Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');
});

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Dashboard utilisateur
    Route::get('/dashboard', [ControllersUserController::class, 'dashboard'])->name('dashboard');
    
    // Profil utilisateur
    Route::prefix('profile')->group(function () {
        Route::get('/edit', [ProfilUserController::class, 'edit'])->name('profil.edit');
        Route::put('/update', [ProfilUserController::class, 'update'])->name('profil.update');
        Route::get('/show', [ProfilUserController::class, 'show'])->name('profile.show');
    });
    
    // Progression
    Route::get('/progression', [UserProgressController::class, 'progression'])->name('progress');
    
    // Badges
    Route::prefix('badges')->group(function () {
        Route::get('/', [BadgeController::class, 'index'])->name('badges.index');
        Route::get('/my', [BadgeController::class, 'myBadges'])->name('badges.my');
        Route::get('/{badge}', [BadgeController::class, 'show'])->name('badges.show');
    });
    
    // Streak
    Route::prefix('streak')->group(function () {
        Route::get('/', [StreakController::class, 'index'])->name('streak.index');
        Route::post('/claim', [StreakController::class, 'claimDailyReward'])->name('streak.claim');
    });

    // Exercices
    Route::prefix('exercises')->group(function () {
        Route::get('/', [ExerciseController::class, 'index'])->name('exercises.index');
        Route::get('/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');
        Route::post('/{exercise}/questions/{question}/submit', [ExerciseController::class, 'submitQuestion'])
            ->name('exercises.questions.submit');
        Route::get('/{exercise}/result', [ExerciseController::class, 'showResult'])
            ->name('exercises.result');
    });
    
    // Questions
    Route::get('/questions/{question}/html', [QuestionController::class, 'html'])
        ->name('questions.html');
    
    // Niveaux
    Route::post('/level/upgrade', [LevelController::class, 'upgrade'])
        ->name('level.upgrade');
    
    // Leçons
    Route::prefix('lessons')->group(function () {
        Route::get('/', [LessonController::class, 'index'])->name('lessons.index');
        Route::get('/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
        Route::post('/{lesson}/complete', [LessonController::class, 'complete'])
            ->name('lessons.complete');
    });
    
    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::post('/mark-all-read2', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    });
    
    Route::post('/notifications/mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.mark-as-read');
});

// Routes du test de niveau
Route::prefix('test')->group(function () {
    Route::get('/start', [LevelTestController::class, 'startTest'])->name('test');
    Route::get('/question', [LevelTestController::class, 'showQuestion'])->name('test.showQuestion');
    Route::post('/answer', [LevelTestController::class, 'submitAnswer'])->name('test.submitAnswer');
    Route::get('/complete', [LevelTestController::class, 'completeTestView'])->name('test.complete');
});

// ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminAdminController::class, 'dashboard'])->name('dashboard');
    Route::redirect('/', '/admin/dashboard', 301);
    
    // Ressources admin
    Route::resource('levels', LevelAdminController::class);
    Route::resource('users', UserController::class);
    Route::resource('lessons', LessonAdminController::class);
    Route::resource('questions', QuestionAdminController::class);
    Route::resource('exercises', ExerciseAdminController::class)->except(['destroy']);
    
    // Questions des exercices
    Route::resource('exercises.questions', QuestionAdminController::class)
        ->shallow()
        ->except(['destroy']);
});