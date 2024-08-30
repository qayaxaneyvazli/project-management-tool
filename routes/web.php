<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('projects.index');
})->name('projects.index');

Route::get('/projects/{id}', function ($id) {
    return view('projects.show', ['id' => $id]);
});
Route::get('/projects/search', [ProjectController::class, 'search'])->name('projects.search');
