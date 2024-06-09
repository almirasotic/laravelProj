<?php

use App\Http\Controllers\CommentController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckModerator;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\NewsFeedController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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


//Home Page
Route::get('/', [ThemeController::class, 'home']);


//Contact Page
Route::get('/contact', [ThemeController::class,'contact']);

//Themes


// All Themes
Route::get('/themes', [ThemeController::class, 'index'] );


//Store Theme Data
Route::post('/themes', [ThemeController::class, 'store'])->middleware(['auth',CheckModerator::class]);

//Show Edit Form
Route::get('/themes/{theme}/edit',[ThemeController::class, 'edit'])->middleware(['auth',CheckModerator::class]);

//Update Theme
Route::put('/themes/{theme}', [ThemeController::class, 'update'])->middleware(['auth',CheckModerator::class]);

//Delete Theme
Route::delete('/themes/{theme}', [ThemeController::class, 'destroy'])->middleware(['auth',CheckModerator::class]);

//Manage Themes
Route::get('/themes/manage', [ThemeController::class , 'manage'])->middleware(['auth', CheckModerator::class]);

//Show Create Form
Route::get('/themes/create', [ThemeController::class, 'create'])->middleware(['auth',CheckModerator::class]);

//Show addComment form
Route::get('/themes/addComment', [CommentController::class, 'create']);


Route::post('/comments/{themeId}', [CommentController::class, 'store']);

Route::delete('/delete-comment/{id}', [CommentController::class, 'destroy']);


//Single Theme
Route::get('/themes/{theme}',[ThemeController::class, 'show']);

Route::get('/themes/{theme}/comments', [CommentController::class, 'show'])->name('themes.comments');


//Accept Reject Themes
Route::post('/themes/{theme}/reject', [ThemeController::class, 'rejectTheme'])->name('themes.reject');

Route::post('/themes/{theme}/accept', [ThemeController::class, 'acceptTheme'])->name('themes.accept');


// Routes for managing moderator requests
Route::post('/users/{user}/reject', [UserController::class, 'rejectModeratorRequest'])->name('users.reject');

Route::post('/users/{user}/accept', [UserController::class, 'acceptModeratorRequest'])->name('users.accept');

//Follow themes
Route::post('themes/{theme}/follow', [ThemeController::class, 'followTheme'])->name('themes.follow');

Route::delete('themes/{theme}/unfollow', [ThemeController::class, 'unfollowTheme'])->name('themes.unfollow');

//User follow themes
Route::get('/followed-themes', [ThemeController::class, 'followedThemes'])->name('followed-themes.index');

//Followers
Route::get('/followers/{themeId}', [ThemeController::class, 'followedThemesIndex'])->name('followed-themes.followers');

//Delete follower
Route::delete('/themes/{themeId}/followers/{followerId}', [ThemeController::class, 'deleteFollower'])->name('followed-themes.delete-follower');



//Anketa

//Show poll
Route::get('/themes/{theme}/details', [ThemeController::class, 'showDetails'])->name('theme.details');

//Create poll form
Route::get('/themes/{theme}/create-poll', [ThemeController::class, 'createPollForm'])->middleware(['auth', CheckModerator::class])->name('themes.create-poll');

//Store poll
Route::post('/themes/{theme}/polls', [ThemeController::class, 'storePoll'])->middleware(['auth', CheckModerator::class])->name('themes.store-poll');

//Save response from poll
Route::post('/poll/response/store', [ThemeController::class, 'storePollResponse'])->name('poll.response.store');

//Delete poll
Route::delete('/poll/{poll}', [ThemeController::class, 'deletePoll'])->middleware(['auth', CheckModerator::class])->name('poll.delete');





//Users

//Manage Users
Route::get('/users/manage', [UserController::class, 'manage'])->middleware(['auth',CheckAdmin::class]);

//Requests Users
Route::get('/users/requests', [UserController::class, 'requests'])->middleware(['auth',CheckAdmin::class]);


//Delete User
Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(['auth',CheckAdmin::class]);

//Show Register Create Form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//Create New User
Route::post('/users', [UserController::class, 'store']);


// Log User Out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');


//Show Login Form
Route::get('/login' , [UserController::class, 'login'])->name('login')->middleware('guest');

//Login User
Route::post('users/authenticate', [UserController::class, 'authenticate']);

//Show Reset Password Form
Route::get('users/{user}/resetPassword', [UserController::class,'reset'])->middleware('auth');

//Reset Password
Route::post('users/{user}',[UserController::class,'resetPassword']);

//Promote to Moderator
Route::post('/users/{user}/promote', [UserController::class, 'promote'])->middleware(['auth',CheckAdmin::class]);

//Demote to Korisnik
Route::post('/users/{user}/demote', [UserController::class, 'demote'])->middleware(['auth',CheckAdmin::class]);

//Apply for Moderator
Route::post('/apply-for-moderator', [NewsFeedController::class, 'applyForModerator'])->middleware('auth');





//Add NewsFeed
Route::post('/add-news-feed' , [NewsFeedController::class, 'create'])->middleware(['auth',CheckAdmin::class]);

//Delete NewsFeed
Route::delete('delete-news-feed/{feed}', [NewsFeedController::class , 'destroy'])->middleware(['auth',CheckAdmin::class]);

//Send Message
Route::post('/send-message', [NewsFeedController::class, 'sendMessage']);





//Email Verification


//Send Email Verification
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');



//Receive Email Verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');



//Resend Verification Email
Route::get('/email/resend', [UserController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.resend');



//Show Resend Verification Email Form
Route::get('/resend-verification', [UserController::class, 'showResendVerificationForm'])
    ->name('resend-verification');
