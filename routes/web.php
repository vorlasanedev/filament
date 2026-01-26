<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PrintEmployeesController;


use App\Http\Controllers\ExportEmployeesPdfController;

Route::get('/print/employees', PrintEmployeesController::class)->name('print.employees');
Route::get('/export/employees/pdf', ExportEmployeesPdfController::class)->name('export.employees.pdf');
