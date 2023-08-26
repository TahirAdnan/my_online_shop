<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
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
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        // Dashboard home page
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Create categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/getSlug', [CategoryController::class, 'getSlug'])->name('getSlug');
        Route::post('/temp-image-create', [TempImageController::class, 'create'])->name('temp-image.create');

        // Edit category
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'delete'])->name('categories.delete');

        // Create sub-categories
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('subCategories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('subCategories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('subCategories.store');

        // Edit sub-category
        Route::get('/sub-categories/{category}/edit', [SubCategoryController::class, 'edit'])->name('subCategories.edit');
        Route::post('/sub-categories/update', [SubCategoryController::class, 'update'])->name('subCategories.update');
        Route::delete('/sub-categories/{category}', [SubCategoryController::class, 'delete'])->name('subCategory.delete');

    });
});