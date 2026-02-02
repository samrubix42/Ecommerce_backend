<?php

use App\Livewire\Admin\Dashboard\Dashboard;
use App\Livewire\Category\CategoryList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::livewire('dashboard', Dashboard::class)
        ->name('admin.dashboard');
    Route::livewire('categories', CategoryList::class)
        ->name('admin.categories');
});

Route::livewire('login', \App\Livewire\Auth\Login::class)
    ->name('login');
Route::get('logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
