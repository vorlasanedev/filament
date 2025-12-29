<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('First Name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(50),
                TextInput::make('last_name')
                    ->label('Last Name')
                    ->required()
                    ->minLength(2)
                    ->maxLength(50),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(50)
                    ->unique(Employee::class, 'email', ignoreRecord: true),
                TextInput::make('phone')
                    ->label('Phone')
                    ->required()
                    ->tel()
                    ->unique(Employee::class, 'phone', ignoreRecord: true)
                    ->rules(['required', 'digits:10'])
                    ->helperText('Enter a 10-digit phone number without space')
                    ->validationAttribute('Phone number')
                    ->maxLength(10)
                    ->placeholder('2000000000'),
                TextInput::make('position')
                    ->label('Job Title')
                    ->required()
                    ->minLength(4) // Corrected here
                    ->maxLength(150),
                TextInput::make('salary')
                    ->label('Salary')
                    ->required()
                    ->numeric()
                    ->minValue(100)
                    ->maxValue(9999999999.99)
                    ->step(0.01)
                    ->placeholder('5000.00')
                    ->helperText('Enter the Salary between 100.00 and 999,999,999.99'),
            ]);
    }
}
