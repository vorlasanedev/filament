<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('John Doe'),
            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255'])
                ->example('john@example.com'),
            ImportColumn::make('phone')
                ->label('Phone')
                ->rules(['nullable', 'max:255'])
                ->example('2055551234'),
            ImportColumn::make('roles')
                ->label('Roles')
                ->relationship('roles', resolveUsing: 'name')
                ->multiple(',')
                ->helperText('Comma-separated role names (e.g. admin, super_admin).')
                ->example('admin'),
            ImportColumn::make('is_active')
                ->label('Is active')
                ->boolean()
                ->rules(['nullable', 'boolean'])
                ->example('yes'),
            ImportColumn::make('password')
                ->label('Password')
                ->ignoreBlankState()
                ->rules(['nullable', 'max:255'])
                ->helperText('Defaults to "password" for new users if left blank.')
                ->example('password123'),
            ImportColumn::make('avatar_url')
                ->label('Avatar URL')
                ->rules(['nullable', 'max:255']),
        ];
    }

    public function resolveRecord(): User
    {
        if (($email = $this->data['email'] ?? null) && $user = User::where('email', $email)->first()) {
            return $user;
        }

        if (($phone = $this->data['phone'] ?? null) && $user = User::where('phone', $phone)->first()) {
            return $user;
        }

        return new User;
    }

    protected function beforeSave(): void
    {
        if (! $this->record->exists && blank($this->record->password)) {
            $this->record->password = 'password';
        }

        if (is_null($this->record->is_active)) {
            $this->record->is_active = true;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    public static function getCompletedNotificationTitle(Import $import): string
    {
        if ($import->successful_rows === 0 && $import->total_rows > 0) {
            return 'Import Failed';
        }

        if ($import->getFailedRowsCount() > 0) {
            return 'Import Completed with Errors';
        }

        return 'Import Completed';
    }
}
