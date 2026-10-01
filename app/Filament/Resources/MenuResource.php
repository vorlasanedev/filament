<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use App\Services\MenuSyncService;
use App\Traits\HasEnterprisePermissions;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class MenuResource extends Resource
{
    use HasEnterprisePermissions;

    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return 'Menu Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('type')
                    ->options([
                        'resource' => 'Resource',
                        'page' => 'Page',
                        'cluster' => 'Cluster',
                        'widget' => 'Widget',
                    ])
                    ->required(),
                TextInput::make('class_name')
                    ->required()
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('navigation_group')
                    ->maxLength(255),
                TextInput::make('navigation_label')
                    ->maxLength(255),
                TextInput::make('navigation_sort')
                    ->numeric(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Menu')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Menu $record) => $record->navigation_label && $record->navigation_label !== $record->name ? $record->navigation_label : null),
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'info' => 'resource',
                        'success' => 'page',
                        'warning' => 'cluster',
                        'gray' => 'widget',
                    ])
                    ->sortable(),
                TextColumn::make('permission_name')
                    ->label('Permission')
                    ->badge()
                    ->color('primary')
                    ->copyable()
                    ->searchable(query: function ($query, string $search) {
                        $query->where('slug', 'like', "%{$search}%");
                    }),
                TextColumn::make('navigation_group')
                    ->label('Group')
                    ->badge()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->afterStateUpdated(function () {
                        app(MenuSyncService::class)->clearCache();
                        Notification::make()
                            ->title('Menu Status Updated')
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'resource' => 'Resource',
                        'page' => 'Page',
                        'cluster' => 'Cluster',
                        'widget' => 'Widget',
                    ]),
                SelectFilter::make('navigation_group')
                    ->options(fn () => Menu::query()->distinct()->whereNotNull('navigation_group')->pluck('navigation_group', 'navigation_group')->toArray()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
        ];
    }
}
