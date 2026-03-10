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
                \Filament\Forms\Components\Select::make('province_id')
                    ->label('Province')
                    ->relationship('province', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Province Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->live()
                    ->afterStateUpdated(function (\Filament\Schemas\Components\Utilities\Set $set) {
                        $set('district_id', null);
                        $set('village_id', null);
                    }),
                \Filament\Forms\Components\Select::make('district_id')
                    ->label('District')
                    ->options(fn (\Filament\Schemas\Components\Utilities\Get $get): \Illuminate\Support\Collection => \App\Models\District::query()
                        ->where('province_id', $get('province_id'))
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('District Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data, \Filament\Schemas\Components\Utilities\Get $get) {
                        if (! $get('province_id')) return null;
                        
                        $district = \App\Models\District::create([
                            'name' => $data['name'],
                            'province_id' => $get('province_id'),
                        ]);
                        return $district->id;
                    })
                    ->live()
                    ->afterStateUpdated(fn (\Filament\Schemas\Components\Utilities\Set $set) => $set('village_id', null)),
                \Filament\Forms\Components\Select::make('village_id')
                    ->label('Village')
                    ->options(fn (\Filament\Schemas\Components\Utilities\Get $get): \Illuminate\Support\Collection => \App\Models\Village::query()
                        ->where('district_id', $get('district_id'))
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Village Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data, \Filament\Schemas\Components\Utilities\Get $get) {
                        if (! $get('district_id')) return null;

                        $village = \App\Models\Village::create([
                            'name' => $data['name'],
                            'district_id' => $get('district_id'),
                        ]);
                        return $village->id;
                    }),
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
                    ->image()
                    ->maxFiles(5)
                    ->directory('employee-galleries')
                    ->panelLayout('grid')
                    ->openable()
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }
}
