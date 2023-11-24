<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AutocompleteController;
use App\Http\Controllers\CatagoryController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\ConfusionController;
use App\Http\Controllers\ConfuseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\Gate\RoleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

use Inertia\Inertia;
use App\Http\Controllers\AuthController;


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

Route::get('/authenticate', [AuthController::class, 'index'])->name('authenticate');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});
Route::post('/', [SearchController::class, 'search']);
Route::get('/search', [SearchController::class, 'searchResult'])->name('search');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/autocomplete', [AutocompleteController::class, 'autocomplete'])->name('autocomplete');
});

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
    
    Route::prefix('gate')->name('gate.')->group(function (){
      Route::prefix('role')->name('role.')->group(function(){
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::delete('/{id}/delete', [RoleController::class, 'destroy'])->name('delete');
        Route::post('/{id}/delete', [RoleController::class, 'update'])->name('edit');
      });
      
      Route::prefix('permission')->name('permission.')->group(function() {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/{id}/form', [PermissionController::class, 'permissionForm'])->name('form');
        Route::post('/{id}/update', [PermissionController::class, 'updateRolePermission'])->name('update');
      });
    });
});

Route::prefix('user')->name('user.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [UserController::class, 'index'])->name('index');
  Route::get('/create', [UserController::class, 'create'])->name('create');
  Route::post('/store', [UserController::class, 'store'])->name('store');
  Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
  Route::post('/{id}/update', [UserController::class, 'update'])->name('update');
  Route::delete('/{user}/delete', [UserController::class, 'delete'])->name('delete');
});

Route::prefix('gate')->name('gate.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::prefix('permission')->name('permission.')->group(function(){
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    Route::post('/store', [PermissionController::class, 'store'])->name('store');
    Route::delete('/{id}/delete', [PermissionController::class, 'destroy'])->name('delete');
    Route::post('/{id}/update', [PermissionController::class, 'update'])->name('update');
  });
});

Route::prefix('calendar')->name('calender.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [EventController::class, 'test']);
  Route::prefix('event')->name('event.')->group(function(){
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/store', [EventController::class, 'store'])->name('store');
    Route::delete('/{id}/delete', [EventController::class, 'delete'])->name('delete');
    Route::get('/{id}/update', [EventController::class, 'edit'])->name('edit');
    Route::post('/{id}/update', [EventController::class, 'update'])->name('update');
  });
  
  Route::prefix('catagory')->name('catagory.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
    Route::get('/', [CatagoryController::class, 'index'])->name('index');
    Route::get('/create', [CatagoryController::class, 'create'])->name('create');
    Route::post('/store', [CatagoryController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [CatagoryController::class, 'edit'])->name('edit');
    Route::post('/edit/{id}', [CatagoryController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [CatagoryController::class, 'delete'])->name('delete');
  });
});

Route::prefix('books')->name('books.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [BooksController::class, 'index'])->name('index');
  Route::get('/create', [BooksController::class, 'create'])->name('create');
  Route::post('/store', [BooksController::class, 'store'])->name('store');
  Route::get('/edit/{id}', [BooksController::class, 'edit'])->name('edit');
  Route::post('/edit/{id}', [BooksController::class, 'update'])->name('update');
  Route::delete('/delete/{id}', [BooksController::class, 'delete'])->name('delete');
});

Route::prefix('confuse')->name('confuse.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [ConfuseController::class, 'index'])->name('index');
  Route::get('/create', [ConfuseController::class, 'create'])->name('create');
  Route::post('/store', [ConfuseController::class, 'store'])->name('store');
  Route::get('/edit/{id}', [ConfuseController::class, 'edit'])->name('edit');
  Route::post('/edit/{id}', [ConfuseController::class, 'update'])->name('update');
  Route::delete('/delete/{id}', [ConfuseController::class, 'delete'])->name('delete');
});

Route::prefix('confusion')->name('confusion.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/{id}', [ConfusionController::class, 'index'])->name('index');
  Route::get('/create', [ConfusionController::class, 'create'])->name('create');
  Route::post('/store', [ConfusionController::class, 'store'])->name('store');
  Route::get('/edit/{id}', [ConfusionController::class, 'edit'])->name('edit');
  Route::post('/edit/{id}', [ConfusionController::class, 'update'])->name('update');
  Route::delete('/delete/{id}', [ConfusionController::class, 'delete'])->name('delete');
});


