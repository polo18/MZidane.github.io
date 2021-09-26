<?php

use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;
use App\Http\Controllers\Front\{
    PostController as FrontPostController,
    CommentController as FrontCommentController,
    ContactController as FrontContactController,
    PageController as FrontPageController
};
use App\Http\Controllers\Back\{
    AdminController,
    PostController as BackPostController,
    UserController as BackUserController,
    ResourceController as BackResourceController
};
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Front-end routes 
|--------------------------------------------------------------------------
| 
*/
Route::get('/', [FrontPostController::class, 'index'])->name('home');
Route::prefix('posts')->group(function () {
    Route::get('{slug}', [FrontPostController::class, 'show'])->name('posts.display');
    Route::get('', [FrontPostController::class, 'search'])->name('posts.search');
    Route::get('{post}/comments', [FrontCommentController::class, 'comments'])->name('posts.comments');
    Route::post('{post}/comments', [FrontCommentController::class, 'store'])->middleware('auth')->name('posts.comments.store');
    
});
Route::get('category/{category:slug}', [FrontPostController::class, 'category'])->name('category');
Route::get('author/{user}', [FrontPostController::class, 'user'])->name('author');
Route::get('tag/{tag:slug}', [FrontPostController::class, 'tag'])->name('tag');
Route::delete('comments/{comment}', [FrontCommentController::class, 'destroy'])->name('front.comments.destroy');
Route::resource('contacts', FrontContactController::class, ['only' => ['create', 'store']]);
Route::get('page/{page:slug}', FrontPageController::class)->name('page');

/*
|--------------------------------------------------------------------------
| Back-end routes 
|--------------------------------------------------------------------------
| 
*/

Route::prefix('admin')->group(function () {
    Route::middleware('redac')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin');
        Route::put('purge/{model}', [AdminController::class, 'purge'])->name('purge');
        Route::resource('posts', BackPostController::class)->except(['show', 'create']);
        Route::get('posts/create/{id?}', [BackPostController::class, 'create'])->name('posts.create');
        // Users
        Route::put('valid/{user}', [BackUserController::class, 'valid'])->name('users.valid');
        Route::put('unvalid/{user}', [BackUserController::class, 'unvalid'])->name('users.unvalid');
        // Comments
        Route::resource('comments', BackResourceController::class)->except(['show', 'create', 'store']);
        Route::get('newcomments', [BackResourceController::class, 'index'])->name('comments.indexnew'); 
    });
    Route::middleware('admin')->group(function () {
        Route::get('newposts', [BackPostController::class, 'index'])->name('posts.indexnew');
        Route::resource('categories', BackResourceController::class)->except(['show']);
        Route::resource('users', BackUserController::class)->except(['show', 'create', 'store']);
        Route::get('newusers', [BackResourceController::class, 'index'])->name('users.indexnew');
        // Contacts
        Route::resource('contacts', BackResourceController::class)->only(['index', 'destroy']);
        Route::get('newcontacts', [BackResourceController::class, 'index'])->name('contacts.indexnew');
        // Follows
        Route::resource('follows', BackResourceController::class)->except(['show']);
        // Pages
        Route::resource('pages', BackResourceController::class)->except(['show']);
    });
});

// Profile
Route::middleware(['auth', 'password.confirm'])->group(function () {
    Route::view('profile', 'auth.profile');
    Route::put('profile', [RegisteredUserController::class, 'update'])->name('profile');
    Route::delete('profile/delete',  [RegisteredUserController::class, 'destroy'])->name('deleteAccount');
});

/** File menagers route */
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => 'auth'], function () {
    Lfm::routes();
});

/** import Auth routes */
require __DIR__.'/auth.php';


