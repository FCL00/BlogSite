<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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

//Admin Routes
Route::get('/admin-only', [AdminController::class, 'adminPage'])->middleware("mustBeLoggedIn", 'can:visitAdminPages'); //middle ware and gate

// User routes
Route::get('/', [UserController::class, 'showHomePage'])->name("login");
Route::post('/register', [UserController::class,'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');

//Follow Related Routes
Route::post('/follow/{user:username}', [FollowController::class, 'followUser'])->middleware('mustBeLoggedIn');
Route::post('/unfollow/{user:username}', [FollowController::class, 'UnFollowUser'])->middleware('mustBeLoggedIn');

// Blog routes
Route::get('/create-post', [PostController::class,'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class,'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{postId}', [PostController::class,'viewSinglePost'])->middleware('mustBeLoggedIn');
Route::delete('/post/{post}', [PostController::class,'delete'])->middleware('mustBeLoggedIn', 'can:delete,post'); //middleware and policy check if the user can delete the post
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('mustBeLoggedIn', 'can:update,post'); //middleware and policy check if the user can update the post
Route::put('/post/{post}', [PostController::class,'updatePost'])->middleware('mustBeLoggedIn','can:update,post');

//Profile related routes
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->middleware('mustBeLoggedIn');// {userData:username} variable name : column name
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing'])->middleware('mustBeLoggedIn');
