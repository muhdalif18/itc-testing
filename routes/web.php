<?php

use App\Http\Controllers\EventProposalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\YourController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/submit-event-proposal-form', function () {
  return view('submit-event-proposal-form');
})->middleware(['auth', 'verified'])->name('submit-event-proposal-form');

Route::get('/generate-event-report-form', function () {
  return view('generate-event-report-form');
})->middleware(['auth', 'verified'])->name('generate-event-report-form');


Route::get('/calendar', function () {
  return view('calendar');
})->middleware(['auth', 'verified'])->name('calendar');

Route::get('/update-profile-form', function () {
  return view('update-profile-form');
})->middleware(['auth', 'verified'])->name('update-profile-form');

Route::get('/createdocument', function () {
  return view('createdocument');
})->middleware(['auth', 'verified'])->name('createdocument');

Route::get('create', 'DocumentController@create');
Route::post('store', 'DocumentController@store');

Route::get('generate-docx', 'HomeController@generateDocx');

Route::get('export-to-word', [EventProposalController::class, 'exportToWord']);



Route::middleware('auth')->group(function () {
  Route::get('/event-proposal', [EventProposalController::class, 'getEventProposal'])->name('event.get-event-proposal');
  Route::post('/event-proposal', [EventProposalController::class, 'postEventProposal'])->name('event.post-event-proposal');

  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
