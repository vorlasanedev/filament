<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class PrintEmployeesController extends Controller
{
    public function __invoke(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        
        $employees = Employee::whereIn('id', $ids)->get();

        return view('filament.pages.print-employees', compact('employees'));
    }
}
