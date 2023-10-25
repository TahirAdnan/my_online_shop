<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Psr7\Request;

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

// // Home page of ReacJS
// Route::get('/', function () {
//     return view('welcome');
// });

// Frontend Routes
    Route::get('/',[FrontController::class, 'index'])->name('front.index');

// Shop Routes    
    Route::get('/shop/{cat_slug?}/{sub_cat_slug?}',[ShopController::class, 'shop'])->name('front.shop');

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


    // Create Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/getSlug', [CategoryController::class, 'getSlug'])->name('getSlug');
        Route::post('/temp-image-create', [TempImageController::class, 'create'])->name('temp-image.create');
        // Edit Category
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'delete'])->name('categories.delete');


    // Create Sub-categories
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('subCategories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('subCategories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('subCategories.store');
        Route::get('/getSubCategory', [SubCategoryController::class, 'getSubCategory'])->name('getSubCategory');
        // Edit Sub-category
        Route::get('/sub-categories/{category}/edit', [SubCategoryController::class, 'edit'])->name('subCategories.edit');
        Route::post('/sub-categories/update', [SubCategoryController::class, 'update'])->name('subCategories.update');
        Route::delete('/sub-categories/{category}', [SubCategoryController::class, 'delete'])->name('subCategory.delete');


    // Create Brands
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        // Edit Brand
        Route::get('/brands/{category}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::post('/brands/update', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{category}', [BrandController::class, 'delete'])->name('brands.delete');    
        
        
    // Create Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        // Edit product
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{update}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'delete'])->name('products.delete');    


    //  Product Image Update
        Route::post('product_image_update/{product_id}', [ProductImageController::class, 'updateProductImage'])->name('productImage.update');
        Route::delete('product_image_delete', [ProductImageController::class, 'productImageDelete'])->name('productImage.delete');    

    });
});