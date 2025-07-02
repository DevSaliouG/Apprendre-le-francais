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



// Authentification
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/', [LevelTestController::class,'home'])->name('home');
});


//ADMIN

// Groupe admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminAdminController::class, 'dashboard'])->name('dashboard');
    
    // Niveaux
    Route::resource('levels', LevelAdminController::class);

    //Users
    Route::resource('users', UserController::class);
    
    // Leçons
    Route::resource('lessons', LessonAdminController::class);
    //  Route::get('/lessons', [LessonAdminController::class, 'index'])->name('lessons');
    
    // Exercices
  //  Route::resource('exercises', ExerciseAdminController::class);
    
    // Questions
    Route::resource('questions', QuestionAdminController::class);

    // Page d'accueil admin
    Route::redirect('/', '/admin/dashboard', 301);
});

// Routes pour les exercices
/* Route::prefix('admin/exercises')->group(function () {
    Route::get('/', [ExerciseAdminController::class, 'index'])->name('admin.exercises.index');
    Route::get('/create', [ExerciseAdminController::class, 'create'])->name('admin.exercises.create');
    Route::post('/', [ExerciseAdminController::class, 'store'])->name('admin.exercises.store');
    Route::get('/{exercise}/edit', [ExerciseAdminController::class, 'edit'])->name('admin.exercises.edit');
    Route::put('/{exercise}', [ExerciseAdminController::class, 'update'])->name('admin.exercises.update');
    Route::delete('/{exercise}', [ExerciseAdminController::class, 'destroy'])->name('admin.exercises.destroy');
});
 */

/* Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Routes pour les exercices
    Route::resource('exercises', ExerciseAdminController::class)->except(['show']);
     Route::get('/exercises', [ExerciseAdminController::class, 'index'])->name('admin.exercises.index');
     Route::get('/exercises/{exercise}/edit', [ExerciseAdminController::class, 'edit'])->name('admin.exercises.edit');
         Route::put('/exercises/{exercise}', [ExerciseAdminController::class, 'update'])->name('admin.exercises.update');

     Route::delete('/exercises/{exercise}', [ExerciseAdminController::class, 'destroy'])->name('admin.exercises.destroy');
    
    // Routes pour les questions (imbriquées)
    Route::prefix('exercises/{exercise}')->group(function () {
        Route::resource('questions', QuestionAdminController::class)
            ->except(['index', 'show']);
    });
    
    // Route supplémentaire pour lister les questions
    Route::get('questions', [QuestionAdminController::class, 'index'])->name('admin.questions.index');
}); */


/*Route::prefix('admin')->name('admin.')->group(function () {
    // Exercices
    Route::get('exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');
    Route::post('exercises', [ExerciseController::class, 'store'])->name('exercises.store');
    Route::get('exercises/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');
    Route::get('exercises/{exercise}/edit', [ExerciseController::class, 'edit'])->name('exercises.edit');
    Route::put('exercises/{exercise}', [ExerciseController::class, 'update'])->name('exercises.update');

    // Questions
    Route::get('exercises/{exercise}/questions/create', [QuestionController::class, 'create'])->name('exercises.questions.create');
    Route::post('exercises/{exercise}/questions', [QuestionController::class, 'store'])->name('exercises.questions.store');
    Route::get('questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
    Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
}); */

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('exercises', ExerciseAdminController::class)->except(['destroy']);
    Route::resource('exercises.questions', QuestionAdminController::class)
        ->shallow()
        ->except(['destroy']);
});




// Routes du test de niveau
Route::prefix('test')->group(function () {
    Route::get('/start', [LevelTestController::class, 'startTest'])->name('test');
    Route::get('/question', [LevelTestController::class, 'showQuestion'])->name('test.showQuestion');
    Route::post('/answer', [LevelTestController::class, 'submitAnswer'])->name('test.submitAnswer');
    Route::get('/complete', [LevelTestController::class, 'completeTestView'])->name('test.complete');
});

// Route de dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ControllersUserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [ProfilUserController::class, 'edit'])->name('profil.edit');
    Route::put('/profile/update', [ProfilUserController::class, 'update'])->name('profil.update');
    Route::get('/profile/show', [ProfilUserController::class, 'show'])->name('profile.show');
    Route::get('/progression', [UserProgressController::class, 'progression'])->name('progress');
});


// Authentification
//Auth::routes();

// Page d'accueil
//Route::get('/', [HomeController::class, 'index'])->name('home');

// Groupe avec middleware d'authentification
Route::middleware(['auth'])->group(function () {
    // Dashboard utilisateur
   // Route::get('/dashboard', [AuthenticatedSessionController::class, 'store'])->name('dashboard');
    
    
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

});

//user exercises
// Routes pour les exercices
Route::middleware(['auth'])->group(function () {
    // Affichage d'un exercice
    Route::get('/exercises/{exercise}', [ExerciseController::class, 'show'])
        ->name('exercises.show');
    
    Route::get('/questions/{question}/html', [QuestionController::class, 'html'])->name('questions.html');

// Route pour la soumission des questions
Route::post('/exercises/{exercise}/questions/{question}/submit', [ExerciseController::class, 'submitQuestion'])->name('exercises.questions.submit');

// Route pour les résultats
Route::get('/exercises/{exercise}/result', [ExerciseController::class, 'showResult'])->name('exercises.result');
    
    // Liste des exercices disponibles
    Route::get('/exercises', [ExerciseController::class, 'index'])
        ->name('exercises.index');
Route::post('/level/upgrade', [LevelController::class, 'upgrade'])->name('level.upgrade');

});


// Notifications
Route::prefix('notifications')->middleware('auth')->group(function () {
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
})->middleware('auth');

//user lessons
Route::middleware(['auth'])->group(function () {
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
});