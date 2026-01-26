<?php

namespace App\Filament\Resources\Employees\Tables;


use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ImportAction;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\EmployeeExporter;
use App\Filament\Imports\EmployeeImporter;
use Filament\Tables\Columns\TextInputColumn;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use Filament\Actions\Action;

use Filament\Tables\Filters\SelectFilter;
use App\Models\Employee;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make('first_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('position')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('salary')
                    ->searchable()
                    ->sortable()
                    ->money('usd')
                    ->toggleable(),
                // ->money('lak'),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->options(Employee::pluck('position', 'position')->unique()),
                Filter::make('salary')
                    ->form([
                        TextInput::make('salary_from')
                            ->numeric()
                            ->label('Salary From'),
                        TextInput::make('salary_to')
                            ->numeric()
                            ->label('Salary To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['salary_from'],
                                fn (Builder $query, $date): Builder => $query->where('salary', '>=', $date),
                            )
                            ->when(
                                $data['salary_to'],
                                fn (Builder $query, $date): Builder => $query->where('salary', '<=', $date),
                            );
                    })
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EmployeesExport, 'employees.xlsx');
                    }),
                // ExportBulkAction::make()
                //     ->exporter(EmployeeExporter::class),
                ImportAction::make()
                    ->importer(EmployeeImporter::class),

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('print')
                        ->label('Print Selected')
                        ->icon('heroicon-o-printer')
                        ->action(fn (Collection $records) => redirect()->route('print.employees', ['ids' => $records->pluck('id')->implode(',')]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
