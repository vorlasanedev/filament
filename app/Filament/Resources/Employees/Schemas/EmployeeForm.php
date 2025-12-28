<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name'),
                TextInput::make('last_name'),
                TextInput::make('email'),
                TextInput::make('phone'),
                TextInput::make('position'),
                TextInput::make('salary'),
            ]);
    }
}
