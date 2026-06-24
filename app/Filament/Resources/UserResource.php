<?php

namespace App\Filament\Resources;

use BackedEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;
    protected static ?string $cluster = \App\Filament\Clusters\UserManagement\UserManagementCluster::class;




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
            ->modifyQueryUsing(fn ($query) => $query->with('employee'))
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->defaultImageUrl(fn ($record) => $record->getFilamentAvatarUrl() ?? "https://ui-avatars.com/api/?name=" . urlencode($record->name))
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([

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
                            $replica->email = 'copy_' . time() . '_' . uniqid() . '@example.com';
                            $replica->save();
                        }
                    })
                    ->deselectRecordsAfterCompletion(),
                \Filament\Actions\BulkAction::make('export_pdf_bulk')
                    ->label('Export Forms (PDF)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        return response()->streamDownload(function () use ($records) {
                            echo \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-export', ['users' => $records])->setPaper('a4', 'portrait')->output();
                        }, 'user-forms-export.pdf');
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
