<?php

namespace App\Filament\Resources\Employees;

use BackedEnum;
use App\Models\Employee;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ViewEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Employees\Tables\EmployeesTable;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static string|\UnitEnum|null $navigationGroup = null;
    protected static ?string $navigationLabel = null;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.employee_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.employees');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.employee');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.employees');
    }

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Employees\RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),

            // to enable model form comment bellow

            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
            'view' => ViewEmployee::route('/{record}/view'),
        ];
    }
}
