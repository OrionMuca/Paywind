<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

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
Route::get('/',[AuthController::class , 'guest'])->name('guest');

Route::get('login',[AuthController::class, 'index'])->name('login');
Route::post('post-login',[AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard.login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('user', [AuthController::class, 'user_index'])->name('user.logged');
Route::get('admin', [AuthController::class, 'admin_index'])->name('admin.logged');

Route::get('user/resetPassword', [AuthController::class, 'reset_pass'])->name('user.resetPass');
Route::get('admin/resetPassword', [AuthController::class, 'reset_passUser'])->name('admin.resetPass');

Route::get('user/updateImage', [AuthController::class, 'image_insert'])->name('user.image');

Route::get('user/update', [AuthController::class, 'user_update'])->name('user.update');

Route::get('fetch_single', [AuthController::class, 'fetch_single'])->name('user.fetch');
Route::get('fetch_all', [AuthController::class, 'fetch_all'])->name('admin.fetchAll');

Route::get('delete', [AuthController::class, 'delete_user'])->name('admin.delete');
Route::get('create', [AuthController::class, 'create_user'])->name('admin.create');

Route::get('payment',[AuthController::class, 'payment'])->name('admin.payment');
Route::get('calculatePayment',[AuthController::class, 'payment_calculate'])->name('admin.calculate');
