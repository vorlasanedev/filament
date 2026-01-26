<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('employee_action')
                ->label(fn () => $this->record->employee ? '1 Employee' : 'Create Employee')
                ->color(fn () => $this->record->employee ? 'gray' : 'primary')
                ->action(function () {
                    if ($this->record->employee) {
                         return redirect(\App\Filament\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $this->record->employee]));
                    }
                    
                    // Create new employee
                    $employee = \App\Models\Employee::create([
                        'user_id' => $this->record->id,
                        'first_name' => explode(' ', $this->record->name)[0],
                        'last_name' => explode(' ', $this->record->name, 2)[1] ?? '',
                        'email' => $this->record->email,
                        'phone' => '',
                        'position' => '',
                        'salary' => 0,
                    ]);

                    return redirect(\App\Filament\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $employee]));
                }),
        ];
    }
}
