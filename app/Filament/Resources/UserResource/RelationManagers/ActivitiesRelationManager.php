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
                        if (! is_array($state) && ! is_object($state)) {
                            return (string) $state;
                        }

                        $formatValue = fn ($v) => is_array($v) || is_object($v) ? json_encode($v) : (string) $v;

                        if ($record->event === 'updated') {
                            $changes = [];
                            $old = $record->properties['old'] ?? [];
                            $old = is_array($old) ? $old : [];
                            
                            foreach ($state as $key => $value) {
                                $newValue = $formatValue($value);
                                if (isset($old[$key]) && $old[$key] !== $value) {
                                    $oldValue = $formatValue($old[$key]);
                                    $changes[] = "{$key}: {$oldValue} -> {$newValue}";
                                } else {
                                    $changes[] = "{$key}: {$newValue}";
                                }
                            }
                            return implode(', ', $changes);
                        }
                        
                        $formatted = [];
                        foreach ($state as $key => $value) {
                            $formatted[] = "{$key}: " . $formatValue($value);
                        }
                        return implode(', ', $formatted);
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
