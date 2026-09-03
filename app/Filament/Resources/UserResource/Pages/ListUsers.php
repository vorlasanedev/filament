<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    #[Url(as: 'view')]
    public string $activeView = 'list';

    public string $kanbanSearch = '';

    public ?string $kanbanRole = null;

    public ?string $kanbanStatus = null;

    #[Url(as: 'grid_page')]
    public int $gridPage = 1;

    public int $gridPerPage = 48;

    public function updatedKanbanSearch(): void
    {
        $this->gridPage = 1;
    }

    public function updatedKanbanRole(): void
    {
        $this->gridPage = 1;
    }

    public function updatedKanbanStatus(): void
    {
        $this->gridPage = 1;
    }

    public function previousGridPage(): void
    {
        if ($this->gridPage > 1) {
            $this->gridPage--;
        }
    }

    public function nextGridPage(): void
    {
        $maxPage = max(1, (int) ceil($this->gridUsersCount / $this->gridPerPage));
        if ($this->gridPage < $maxPage) {
            $this->gridPage++;
        }
    }

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

    public function content(Schema $schema): Schema
    {
        if (in_array($this->activeView, ['grid', 'kanban'])) {
            return $schema
                ->components([
                    \Filament\Schemas\Components\View::make('filament.resources.user-resource.pages.user-kanban-board'),
                ]);
        }

        return parent::content($schema);
    }

    protected function getGridQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return User::query()
            ->with(['roles', 'employee'])
            ->when($this->kanbanSearch, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('phone', 'ilike', "%{$search}%");
                });
            })
            ->when($this->kanbanRole, function ($query, $role) {
                $query->whereHas('roles', fn ($q) => $q->where('name', $role));
            })
            ->when($this->kanbanStatus !== null && $this->kanbanStatus !== '', function ($query) {
                $query->where('is_active', $this->kanbanStatus === 'active');
            })
            ->latest('id');
    }

    public function getGridUsersProperty(): Collection
    {
        $offset = ($this->gridPage - 1) * $this->gridPerPage;

        return $this->getGridQuery()
            ->offset($offset)
            ->limit($this->gridPerPage)
            ->get();
    }

    public function getGridUsersCountProperty(): int
    {
        return $this->getGridQuery()->count();
    }

    public function getActiveUsersCountProperty(): int
    {
        return User::where('is_active', true)->count();
    }

    public function getInactiveUsersCountProperty(): int
    {
        return User::where('is_active', false)->count();
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

    public function editUserAction(): Actions\EditAction
    {
        return Actions\EditAction::make('editUser')
            ->record(fn (array $arguments) => User::find($arguments['id'] ?? null))
            ->schema(fn (Schema $schema) => UserResource::form($schema))
            ->successNotificationTitle('User updated successfully');
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
