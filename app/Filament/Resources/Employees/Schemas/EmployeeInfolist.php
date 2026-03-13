<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Alsaloul\ImageGallery\Infolists\Entries\ImageGalleryEntry;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee Details')
                    ->components([
                        ImageEntry::make('profile_picture')
                            ->hiddenLabel()
                            ->circular()
                            ->imageSize(120),
                        Grid::make(3)
                            ->components([
                                TextEntry::make('first_name')->label(__('fields.first_name')),
                                TextEntry::make('last_name')->label(__('fields.last_name')),
                                TextEntry::make('email')->label(__('fields.email')),
                                TextEntry::make('phone')->label(__('fields.phone')),
                                TextEntry::make('position.name')->label(__('fields.position')),
                                TextEntry::make('salary')->label(__('fields.salary'))->money('usd'),
                                TextEntry::make('province.name')->label('Province'),
                                TextEntry::make('district.name')->label('District'),
                                TextEntry::make('village.name')->label('Village'),
                            ]),
                    ]),
                Section::make('Gallery')
                    ->components([
                        ImageGalleryEntry::make('gallery')
                            ->hiddenLabel()
                            ->disk('public')
                            ->thumbWidth(150)
                            ->thumbHeight(150)
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
