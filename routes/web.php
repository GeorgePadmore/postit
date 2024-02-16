<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::post('/posts/search', [PostController::class, 'search'])->name('posts.search');

Route::get('/posts/details/{id}', [PostController::class, 'details'])->name('posts.details');
Route::post('/posts', [PostController::class, 'create'])->name('posts.create');
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::post('/posts/update', [PostController::class, 'update'])->name('posts.update');
Route::post('/posts/delete/{id}', [PostController::class, 'delete'])->name('posts.delete');

Route::post('/posts/{postId}/comments/add', [CommentController::class, 'add'])->name('comments.add');

Route::post('/posts/{postId}/like/add', [PostController::class, 'likePost'])->name('posts.likePost');
Route::post('/posts/{postId}/like/remove', [PostController::class, 'unlikePost'])->name('posts.unlikePost');

Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::post('/comments/update', [CommentController::class, 'update'])->name('comments.update');

Route::post('/comments/{commentId}/like/add', [CommentController::class, 'likeComment'])->name('comments.likeComment');
Route::post('/comments/{commentId}/like/remove', [CommentController::class, 'unlikeComment'])->name('comments.unlikeComment');

Route::get('/posts/send-email', [MailController::class, 'notifyNewCommentEmail'])->name('posts.notifyNewCommentEmail');

require __DIR__.'/auth.php';
