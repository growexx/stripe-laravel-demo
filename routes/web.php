<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('plans.dashboard');
})->middleware(['auth'])->name('dashboard');

// Route::get('/', function () {
//     return view('404');
// });
require __DIR__.'/auth.php';


// Route::group(['middleware' => 'auth'], function() {
//     Route::get('/home', [HomeController::class, 'index'])->name('home');
//     Route::get('/plans', [PlanController::class, 'index'])->name('index');
//     Route::get('/plan/{plan}', [PlanController::class, 'show'])->name('show');
//     Route::post('/subscription', [SubscriptionController::class, 'create'])->name('subscription.create');

//     //Routes for create Plan
//     Route::get('create/plan', [SubscriptionController::class, 'createPlan'])->name('create.plan');
//     Route::post('store/plan', [SubscriptionController::class, 'storePlan'])->name('store.plan');
// });

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::resource('userSub', 'SubscriptionController');
    Route::resource('user', 'UserController');
    Route::resource('invoice', 'InvoiceController');
    Route::resource('subscription', 'AdminSubController');
    Route::resource('plans', 'PlanController');
    Route::resource('payment', 'PaymentController')->middleware('subscribe');
    Route::resource('userSubscription', 'UserSubController')->middleware('subscribe');
});

