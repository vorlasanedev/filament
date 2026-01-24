<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;

use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->label('New Data'),
                 Forms\Components\KeyValue::make('properties.old')
                    ->label('Old Data'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Event')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ]),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User'),
                Tables\Columns\TextColumn::make('properties.attributes')
                    ->label('Changes')
                    ->wrap()
                    ->formatStateUsing(function ($state, Activity $record) {
                        if ($record->event === 'updated') {
                            $changes = [];
                            $old = $record->properties['old'] ?? [];
                            foreach ($state as $key => $value) {
                                if (isset($old[$key]) && $old[$key] !== $value) {
                                  $changes[] = "$key: $old[$key] -> $value";
                                } else {
                                     $changes[] = "$key: $value";
                                }
                            }
                            return implode(', ', $changes);
                        }
                         if (is_array($state)) {
                             $formatted = [];
                             foreach ($state as $key => $value) {
                                 $formatted[] = "$key: $value";
                             }
                             return implode(', ', $formatted);
                         }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
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
                //
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
