<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PrintEmployeesController;

Route::get('/print/employees', PrintEmployeesController::class)->name('print.employees');
