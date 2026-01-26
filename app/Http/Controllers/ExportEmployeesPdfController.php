<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportEmployeesPdfController extends Controller
{
    public function __invoke(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        
        $employees = Employee::whereIn('id', $ids)->get();

        $pdf = Pdf::loadView('filament.pages.employees-pdf', compact('employees'));

        if ($request->has('preview')) {
            return $pdf->stream('employees-report.pdf');
        }

        return $pdf->download('employees-report.pdf');
    }
}
