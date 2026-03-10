<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PrintEmployeesController;


use App\Http\Controllers\ExportEmployeesPdfController;

Route::get('/print/employees', PrintEmployeesController::class)->name('print.employees');
Route::get('/export/employees/pdf', ExportEmployeesPdfController::class)->name('export.employees.pdf');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);
    
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }
    
    if (! $request->hasValidSignature()) {
        abort(403, 'Invalid or expired signature.');
    }
    
    $user->markEmailAsVerified();
    
    return redirect()->to(filament()->getLoginUrl());
})->name('verification.verify');
Route::get('/email/resend/{user}', function (\App\Models\User $user) {
    if (!$user->is_active) {
        $user->sendEmailVerificationNotification();
        session()->flash('status', 'Sent confirm email to ' . $user->email);
    }
    
    return redirect()->to(filament()->getLoginUrl());
})->name('verification.resend');
