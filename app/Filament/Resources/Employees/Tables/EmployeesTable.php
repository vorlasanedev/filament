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
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Action as TableAction;
use Filament\Actions\ActionGroup as TableActionGroup;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\EmployeeExporter;
use App\Filament\Imports\EmployeeImporter;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ImageColumn;
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
            ->striped()
            ->columns([
                ImageColumn::make('profile_picture')
                    ->circular(),
                ImageColumn::make('gallery')
                    ->circular()
                    ->stacked()
                    ->limit(3),
                TextInputColumn::make('first_name')
                    ->label(__('fields.first_name'))
                    ->extraAttributes(['class' => 'py-1'])
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('last_name')
                    ->label(__('fields.last_name'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label(__('fields.email'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label(__('fields.phone'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('position.name')
                    ->label(__('fields.position'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('salary')
                    ->label(__('fields.salary'))
                    ->searchable()
                    ->sortable()
                    ->money('usd')
                    ->toggleable(),
                // ->money('lak'),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->relationship('position', 'name')
                    ->label(__('fields.position')),
                Filter::make('salary')
                    ->label(__('fields.salary'))
                    ->schema([
                        TextInput::make('salary_from')
                            ->numeric()
                            ->label(__('fields.salary_from')),
                        TextInput::make('salary_to')
                            ->numeric()
                            ->label(__('fields.salary_to')),
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

                // ExportBulkAction::make()
                //     ->exporter(EmployeeExporter::class),
                // ImportAction::make()
                //     ->importer(EmployeeImporter::class),

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                // DeleteAction::make(),
                TableActionGroup::make([
                    TableAction::make('print')
                        ->label('Print')
                        ->icon('heroicon-o-printer')
                        ->url(fn (Employee $record) => route('print.employees', ['ids' => $record->id]))
                        ->openUrlInNewTab(),
                    TableAction::make('preview_pdf')
                        ->label('Preview PDF')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Employee $record) => route('export.employees.pdf', ['ids' => $record->id, 'preview' => true, 'locale' => app()->getLocale()]))
                        ->openUrlInNewTab(),
                    DeleteAction::make(),
                ]),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),

                BulkAction::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (Collection $records) => Excel::download(new EmployeesExport($records), 'employees.xlsx'))
                    ->deselectRecordsAfterCompletion(),
                    
                BulkAction::make('print')
                    ->label('Print Selected')
                    ->icon('heroicon-o-printer')
                    ->action(fn (Collection $records) => redirect()->route('print.employees', ['ids' => $records->pluck('id')->implode(',')]))
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('preview_pdf')
                    ->label('Preview PDF')
                    ->icon('heroicon-o-eye')
                    ->action(function (Collection $records, \Livewire\Component $livewire) {
                        $url = route('export.employees.pdf', [
                            'ids' => $records->pluck('id')->implode(','), 
                            'preview' => true, 
                            'locale' => app()->getLocale()
                        ]);
                        
                        $livewire->js("window.open('{$url}', '_blank')");
                    })
                    ->deselectRecordsAfterCompletion(),
                
                BulkAction::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (Collection $records) => redirect()->route('export.employees.pdf', ['ids' => $records->pluck('id')->implode(','), 'locale' => app()->getLocale()]))
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}
