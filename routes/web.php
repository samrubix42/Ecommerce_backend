<?php

use App\Livewire\Admin\Dashboard\Dashboard;
use App\Livewire\Admin\Product\AddProduct;
use App\Livewire\Admin\Product\ProductList;
use App\Livewire\Admin\Product\UpdateProduct;
use App\Livewire\Admin\Product\ManageAttributeValue;
use App\Livewire\Category\CategoryList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::livewire('/', Dashboard::class)
        ->name('admin.dashboard');
    Route::livewire('categories', CategoryList::class)
        ->name('admin.categories');
    Route::livewire('attributes', ManageAttributeValue::class)
        ->name('admin.attributes');
    Route::livewire('products', ProductList::class)
        ->name('admin.products.index');
    Route::livewire('add-product', AddProduct::class)
        ->name('admin.add-product');
    Route::livewire('update-product/{product}', UpdateProduct::class)
        ->name('admin.update-product');
});

Route::livewire('login', \App\Livewire\Auth\Login::class)
    ->name('login');
Route::get('logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
