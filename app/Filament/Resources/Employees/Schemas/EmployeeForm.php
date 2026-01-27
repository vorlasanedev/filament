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
                    ->label(__('fields.first_name'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(50)
                    ->formatStateUsing(fn ($record) => $record?->first_name),
                TextInput::make('last_name')
                    ->label(__('fields.last_name'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(50)
                    ->formatStateUsing(fn ($record) => $record?->last_name),
                TextInput::make('email')
                    ->label(__('fields.email'))
                    ->required()
                    ->email()
                    ->maxLength(50)
                    ->unique(Employee::class, 'email', ignoreRecord: true),
                TextInput::make('phone')
                    ->label(__('fields.phone'))
                    ->required()
                    ->tel()
                    ->unique(Employee::class, 'phone', ignoreRecord: true)
                    ->rules(['required', 'digits:10'])
                    ->helperText('Enter a 10-digit phone number without space')
                    ->validationAttribute('Phone number')
                    ->maxLength(10)
                    ->placeholder('2000000000'),
                \Filament\Forms\Components\Select::make('position_id')
                    ->label(__('fields.position'))
                    ->relationship('position', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label(__('fields.name'))
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionModalHeading(__('navigation.create_position'))
                    ->required(),
                TextInput::make('salary')
                    ->label(__('fields.salary'))
                    ->required()
                    ->numeric()
                    ->minValue(100)
                    ->maxValue(9999999999.99)
                    ->step(0.01)
                    ->placeholder('5000.00')
                    ->helperText('Enter the Salary between 100.00 and 999,999,999.99'),
                \Filament\Forms\Components\FileUpload::make('profile_picture')
                    ->avatar()
                    ->directory('employee-photos')
                    ->columnSpanFull(),
                \Filament\Forms\Components\FileUpload::make('gallery')
                    ->multiple()
                    ->maxFiles(5)
                    ->directory('employee-galleries')
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }
}
