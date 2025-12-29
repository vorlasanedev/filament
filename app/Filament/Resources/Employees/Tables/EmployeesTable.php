<?php

namespace App\Filament\Resources\Employees\Tables;


use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ImportAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\EmployeeExporter;
use App\Filament\Imports\EmployeeImporter;
use Filament\Tables\Columns\TextInputColumn;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('salary')
                    ->searchable()
                    ->sortable()
                    ->money('usd'),
                // ->money('lak'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportBulkAction::make()
                    ->exporter(EmployeeExporter::class),
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
                ]),
            ]);
    }
}
