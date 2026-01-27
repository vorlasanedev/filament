<?php

namespace App\Filament\Resources\PositionResource\Pages;

use App\Filament\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPositions extends ListRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('navigation.create_position')),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
