<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;

class EmployeesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithTitle, WithEvents
{
    protected ?Collection $records;

    public function __construct(?Collection $records = null)
    {
        $this->records = $records;
    }

    public function query()
    {
        if ($this->records) {
            return Employee::query()->whereIn('id', $this->records->pluck('id'));
        }

        return Employee::query();
    }

    public function headings(): array
    {
        return [
            'ID',
            'First name',
            'Last name',
            'Email',
            'Phone',
            'Position',
            'Salary',
            'Created at',
            'Updated at',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->first_name,
            $employee->last_name,
            $employee->email,
            $employee->phone,
            $employee->position,
            $employee->salary,
            $employee->created_at,
            $employee->updated_at,
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function title(): string
    {
        return 'Employees';
    }

    public function styles(Worksheet $sheet)
    {
        // Set default font for the entire sheet to Phetsarath OT as seen in the image
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Phetsarath OT');

        return [
            // Style the header row (Row 2)
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'BDD7EE'], // Light blue as seen in image
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $lastColumn = 'I';
                
                // Merge Title Row
                $sheet->mergeCells("A1:{$lastColumn}1");
                
                // Set Title Text and specifically style it
                $sheet->setCellValue('A1', 'ລາຍການພະນັກງານ');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'name' => 'Phetsarath OT',
                        'bold' => true,
                        'size' => 20, // Increased to 20 as requested
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Apply borders to all data cells (from Row 3 to highest data row)
                if ($highestRow >= 3) {
                    $sheet->getStyle("A3:{$lastColumn}{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }

                // Add Footer Metadata (Export By / Export Date)
                $footerLabelRow = $highestRow + 2;
                $footerValueRow = $highestRow + 3;

                // Labels
                $sheet->setCellValue("H{$footerLabelRow}", 'Export By');
                $sheet->setCellValue("I{$footerLabelRow}", 'Export Date');

                // Values
                $userName = auth()->user()?->name ?? 'System';
                $exportDate = now()->format('j/n/Y'); // format like 8/1/2026
                $sheet->setCellValue("H{$footerValueRow}", $userName);
                $sheet->setCellValue("I{$footerValueRow}", $exportDate);

                // Style Footer Labels
                $sheet->getStyle("H{$footerLabelRow}:I{$footerLabelRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'BDD7EE'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Style Footer Values
                $sheet->getStyle("H{$footerValueRow}:I{$footerValueRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Auto size columns
                foreach (range('A', $lastColumn) as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
