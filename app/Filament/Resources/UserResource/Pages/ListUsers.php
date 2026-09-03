<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    #[Url(as: 'view')]
    public string $activeView = 'list';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(\App\Filament\Imports\UserImporter::class)
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\CreateAction::make()
                ->before(function ($livewire) {
                    $livewire->resetTableSearch();
                }),
        ];
    }

    public function updateUserStatus(int $userId, bool $isActive): void
    {
        $user = User::find($userId);

        if (! $user) {
            return;
        }

        $user->update(['is_active' => $isActive]);

        Notification::make()
            ->title('User Status Updated')
            ->body("User [{$user->name}] is now ".($isActive ? 'Active' : 'Inactive').'.')
            ->success()
            ->send();
    }

    public function getPageClasses(): array
    {
        return [
            ...parent::getPageClasses(),
            ...($this->activeView === 'grid' ? ['is-grid-view'] : []),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
