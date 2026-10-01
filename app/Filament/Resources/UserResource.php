<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use App\Traits\HasEnterprisePermissions;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasEnterprisePermissions;
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Users';

    public static function getNavigationLabel(): string
    {
        return __('navigation.user_list');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.user');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.users');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Forms\Components\FileUpload::make('avatar_url')
                    ->avatar()
                    ->directory('avatars'),
                Forms\Components\Toggle::make('is_active')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['employee', 'roles']))
            ->content(function ($livewire) {
                if (isset($livewire->activeView) && in_array($livewire->activeView, ['grid', 'kanban'])) {
                    return view('filament.resources.user-resource.pages.user-grid-content');
                }

                return null;
            })
            ->paginationPageOptions([12, 24, 48, 96])
            ->defaultPaginationPageOption(24)
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->defaultImageUrl(fn ($record) => $record->getFilamentAvatarUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($record->name))
                    ->circular()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordAction('view_profile')
            ->recordActions([
                \Filament\Actions\Action::make('view_profile')
                    ->label('View Profile')
                    ->icon('heroicon-o-identification')
                    ->color('info')
                    ->slideOver()
                    ->modalWidth('5xl')
                    ->modalHeading(fn (?User $record) => $record ? "User Profile: {$record->name}" : 'User Profile')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (?User $record) => $record ? view('filament.resources.user-resource.modals.user-profile-view', [
                        'user' => $record,
                        'profileData' => app(\App\Services\UserProfileService::class)->getProfileData($record),
                    ]) : null),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->groupedBulkActions([
                \Filament\Actions\BulkAction::make('duplicate')
                    ->label('Duplicate Row')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        foreach ($records as $record) {
                            $replica = $record->replicate(['email', 'phone']);
                            $replica->email = 'copy_'.time().'_'.uniqid().'@example.com';
                            $replica->save();
                        }
                    })
                    ->deselectRecordsAfterCompletion(),
                \Filament\Actions\BulkAction::make('export_pdf_bulk')
                    ->label('Export Forms (PDF)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        \App\Jobs\ExportUsersPdfJob::dispatch($records->pluck('id')->toArray(), auth()->id());

                        \Filament\Notifications\Notification::make()
                            ->title('Export Started')
                            ->body('Your PDF export has been queued and will be ready shortly. You will receive a notification when it is done.')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                \Filament\Actions\ExportBulkAction::make()
                    ->exporter(\App\Filament\Exports\UserExporter::class),
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UserResource\RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
