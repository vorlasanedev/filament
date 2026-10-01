<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles;

use App\Filament\Forms\Components\PermissionMatrix;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Traits\HasEnterprisePermissions;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    use HasEnterprisePermissions;

    protected static ?int $navigationSort = 2;

    public static function getSubNavigationPosition(): \Filament\Pages\Enums\SubNavigationPosition
    {
        return \Filament\Pages\Enums\SubNavigationPosition::Top;
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make('Role Details')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Role Name')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('guard_name')
                                    ->label('Guard Name')
                                    ->default('web')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),

                        Section::make('Permission Matrix')
                            ->description('Control granular access per Model, Page, Cluster, and Widget in real-time.')
                            ->schema([
                                PermissionMatrix::make('permissions')
                                    ->hiddenLabel()
                                    ->afterStateHydrated(function ($component, ?Role $record) {
                                        if (! $record) {
                                            $component->state([]);
                                            return;
                                        }
                                        $component->state($record->permissions->pluck('name')->toArray());
                                    })
                                    ->dehydrated(true)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->weight(FontWeight::Medium)
                    ->label('Role Name')
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->badge()
                    ->color('warning')
                    ->label('Guard'),
                TextColumn::make('permissions_count')
                    ->badge()
                    ->label('Permissions')
                    ->counts('permissions')
                    ->color('primary'),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth('7xl')
                    ->after(function (Role $record, array $data) {
                        static::syncPermissionsFromFormData($record, $data);
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * Synchronize permissions from matrix form data into the role.
     */
    public static function syncPermissionsFromFormData(Role $record, array $data): void
    {
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $record->syncPermissions(array_values(array_unique($data['permissions'])));
        }
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
        ];
    }

    public static function getModel(): string
    {
        return Role::class;
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return 'roles';
    }

    public static function getCluster(): ?string
    {
        return \App\Filament\Clusters\UserManagement\UserManagementCluster::class;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Configuration';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
}
