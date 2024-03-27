<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/w', function () {
   echo 'welcome';
})->name('welcome');

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([
    'middleware' => 'auth',
    ],function (){
    Route::resource('/posts',PostController::class);
    Route::get('/user-posts',[PostController::class,'userPosts'])->middleware('auth');
    Route::get('posts/restore/{id}', [PostController::class, 'restore'])->name('posts.restore');
    Route::get('posts/restore-all', [PostController::class, 'restoreAll'])->name('posts.restoreAll');
   Route ::post('create_comment/{pid}', [CommentController::class, 'store'])->name('create_comment');
   Route::get('users/unblock', [UserController::class, 'unBlock']);
}

);

Route::group([
    'middleware' => ['auth','admin'],
    ],function (){
        Route::get('admin/all_users', [AdminController::class, 'all']);
        Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [AdminController::class, 'update'])->name('edit');
        Route::delete('/delete/{id}', [AdminController::class, 'delete'])->name('delete');
}

);
Route::get('/addloginfo', function () {
    log::info('show post');
});
Route::post('/upload_ids', [UserController::class, 'uploadIds'])->middleware('uploadIdMiddle');
Route::get('/all_users', [UserController::class, 'all']);

Route::get('download-id',[UserController::class, 'downloadId'])->name('download_ids');