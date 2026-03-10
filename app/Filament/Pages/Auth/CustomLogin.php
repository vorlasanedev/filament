<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\HtmlString;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class CustomLogin extends BaseLogin
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(#[\SensitiveParameter] array $data): array
    {
        return parent::getCredentialsFromFormData($data);
    }

    protected function throwFailureValidationException(): never
    {
        $data = $this->form->getState();
        $user = User::where('email', $data['email'])->first();

        if ($user && Hash::check($data['password'], $user->password) && !$user->is_active) {
            Notification::make()
                ->title('your user email is not confirm')
                ->body('Please click confirm email below to send a new confirmation link.')
                ->danger()
                ->actions([
                    \Filament\Actions\Action::make('resend')
                        ->label('click confirm email')
                        ->button()
                        ->url(route('verification.resend', ['user' => $user->id]))
                ])
                ->persistent()
                ->send();

            throw ValidationException::withMessages([
                'data.email' => 'your user email is not confirm.',
            ]);
        }

        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
