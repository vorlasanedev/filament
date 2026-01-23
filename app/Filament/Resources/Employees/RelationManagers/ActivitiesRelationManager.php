<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Spatie\Activitylog\Models\Activity;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activity Log';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\KeyValue::make('properties.attributes')
                    ->label('New Values'),
                Forms\Components\KeyValue::make('properties.old')
                    ->label('Old Values'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User'),
                Tables\Columns\TextColumn::make('properties.attributes')
                    ->label('Changes')
                    ->formatStateUsing(function ($state) {
                         if (empty($state)) return null;
                         return collect($state)
                             ->map(fn ($value, $key) => "$key: $value")
                             ->join(', ');
                    })
                    ->wrap()
                    ->limit(100),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
