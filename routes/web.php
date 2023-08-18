<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\TempImageController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

//  Admin panel routes
Route::group(['prefix' => 'admin'], function(){
    Route::group(['middleware' => 'admin.guest'], function() {
        Route::get('/login', [AdminLoginController::class, 'index'] )->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'] )->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function() {
        // Dashboard home page
        Route::get('/dashboard', [HomeController::class, 'index'] )->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'] )->name('admin.logout');

        // Create category
        Route::get('/categories', [CategoryController::class, 'index'] )->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'] )->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'] )->name('categories.store');
        Route::get('/getSlug', [CategoryController::class, 'getSlug'] )->name('getSlug');
        Route::post('/temp-image-create', [TempImageController::class, 'create'] )->name('temp-image.create');
    });
});
