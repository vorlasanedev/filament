<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportEmployeesPdfController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->has('locale')) {
            app()->setLocale($request->get('locale'));
        }

        $ids = explode(',', $request->query('ids', ''));
        
        $employees = Employee::whereIn('id', $ids)->get();

        $fontFamily = app()->getLocale() === 'lo' ? 'Phetsarath OT' : 'sans-serif';

        $pdf = Pdf::loadView('filament.pages.employees-pdf', compact('employees', 'fontFamily'));

        if ($request->has('preview')) {
            return $pdf->stream('employees-report.pdf');
        }

        return $pdf->download('employees-report.pdf');
    }
}
