<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ContentPageController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [AdminLoginController::class, 'show'])->name('admin');
Route::post('/admin', [AdminLoginController::class, 'login'])->middleware('throttle:5,1')->name('admin.login');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureAdmin::class])->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/wishlist', 'wishlist.index')->name('wishlist.index');

Route::get('/festive-favorites', [CollectionController::class, 'festiveFavorites'])->name('festive-favorites');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/festive-favorites', [CollectionController::class, 'festiveFavoritesCollection'])->name('collections.festive-favorites');
Route::get('/collections/coord-sets', [CollectionController::class, 'coordSets'])->name('collections.coord-sets');
Route::get('/collections/designer-kurtas', [CollectionController::class, 'designerKurtas'])->name('collections.designer-kurtas');
Route::get('/collections/wedding-edit', [CollectionController::class, 'weddingEdit'])->name('collections.wedding-edit');
Route::get('/collections/autumn-edit', [CollectionController::class, 'autumnEdit'])->name('collections.autumn-edit');
Route::get('/collections/everyday-elegance', [CollectionController::class, 'everydayElegance'])->name('collections.everyday-elegance');
Route::get('/collections/pastel-stories', [CollectionController::class, 'pastelStories'])->name('collections.pastel-stories');
Route::get('/collections/just-added', [CollectionController::class, 'justAdded'])->name('collections.just-added');
Route::get('/collections/ethnic-wear', [CollectionController::class, 'ethnicWear'])->name('collections.ethnic-wear');
Route::get('/collections/cord-set', [CollectionController::class, 'cordSet'])->name('collections.cord-set');
Route::get('/collections/gown', [CollectionController::class, 'gown'])->name('collections.gown');
Route::get('/collections/indo-western', [CollectionController::class, 'indoWestern'])->name('collections.indo-western');
Route::get('/collections/fusion-wear', [CollectionController::class, 'fusionWear'])->name('collections.fusion-wear');

Route::get('/pages/about-us', [ContentPageController::class, 'aboutUs'])->name('pages.about-us');
Route::get('/pages/contact-us', [ContentPageController::class, 'contactUs'])->name('pages.contact-us');
Route::get('/pages/faqs', [ContentPageController::class, 'faqs'])->name('pages.faqs');
Route::get('/pages/news', [ContentPageController::class, 'news'])->name('pages.news');
Route::get('/pages/privacy-policy', [ContentPageController::class, 'privacyPolicy'])->name('pages.privacy-policy');
Route::get('/pages/shipping-returns', [ContentPageController::class, 'shippingReturns'])->name('pages.shipping-returns');
Route::get('/pages/terms-conditions', [ContentPageController::class, 'termsConditions'])->name('pages.terms-conditions');
Route::get('/pages/return-exchange', [ContentPageController::class, 'returnExchange'])->name('pages.return-exchange');
