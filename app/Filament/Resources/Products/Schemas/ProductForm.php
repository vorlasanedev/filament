<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Group::make()->schema([
                        TextInput::make('name')
                            ->hiddenLabel()
                            ->placeholder('Product Name (e.g. Cheese Burger)')
                            ->required()
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem; font-weight: 600;']),
                            
                        Grid::make(3)->schema([
                            Checkbox::make('can_be_sold')
                                ->label('Can be sold')
                                ->default(true),
                            Checkbox::make('can_be_purchased')
                                ->label('Can be purchased')
                                ->default(true),
                            Toggle::make('is_favorite')
                                ->label('Favorite')
                                ->onIcon('heroicon-s-star')
                                ->offIcon('heroicon-o-star'),
                        ]),
                    ])->columnSpan(2),
                    
                    Group::make()->schema([
                        FileUpload::make('image')
                            ->hiddenLabel()
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->alignCenter(),
                    ])->columnSpan(1),
                ]),

                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('General Information')
                            ->schema([
                                Grid::make(2)->schema([
                                    // Left Column
                                    Group::make()->schema([
                                        Radio::make('product_type_id')
                                            ->options(fn () => \App\Models\ProductType::pluck('name', 'id'))
                                            ->label('Product Type')
                                            ->inline()
                                            ->required()
                                            ->helperText('Choose the type of product.'),
                                            
                                        Checkbox::make('track_inventory')
                                            ->label('Track Inventory')
                                            ->default(true)
                                            ->helperText('Check if you want to track stock levels.'),
                                            
                                        Radio::make('invoicing_policy')
                                            ->label('Invoicing Policy')
                                            ->options([
                                                'ordered' => 'Ordered quantities',
                                                'delivered' => 'Delivered quantities',
                                            ])
                                            ->default('ordered')
                                            ->helperText('You can invoice goods before they are delivered.')
                                            ->hint('Determine when the product can be invoiced.'),
                                    ]),

                                    // Right Column
                                    Group::make()->schema([
                                        TextInput::make('price')
                                            ->label('Sales Price')
                                            ->required()
                                            ->numeric()
                                            ->default(0.0)
                                            ->prefix('$')
                                            ->helperText('The price at which this product is sold.'),
                                            
                                        Select::make('sales_taxes')
                                            ->label('Sales Taxes')
                                            ->multiple()
                                            ->options([
                                                '15%' => '15%',
                                                '10%' => '10%',
                                                '5%' => '5%',
                                                '0%' => '0%',
                                            ])
                                            ->helperText('Applicable taxes when selling this product.'),
                                            
                                        TextInput::make('cost')
                                            ->label('Cost')
                                            ->required()
                                            ->numeric()
                                            ->default(0.0)
                                            ->prefix('$')
                                            ->helperText('The cost of purchasing or manufacturing this product.'),
                                            
                                        Select::make('purchase_taxes')
                                            ->label('Purchase Taxes')
                                            ->multiple()
                                            ->options([
                                                '15%' => '15%',
                                                '10%' => '10%',
                                                '5%' => '5%',
                                                '0%' => '0%',
                                            ])
                                            ->helperText('Applicable taxes when purchasing this product.'),
                                            
                                        Select::make('product_category_id')
                                            ->relationship('category', 'name')
                                            ->label('Category')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required()
                                            ]),
                                            
                                        TextInput::make('sku')
                                            ->label('Reference')
                                            ->placeholder('e.g. SKU-0001'),
                                            
                                        TextInput::make('barcode')
                                            ->label('Barcode')
                                            ->placeholder('e.g. 1234567890123'),
                                    ]),
                                ]),
                            ]),
                        
                        Tabs\Tab::make('Sales')
                            ->schema([
                                // Placeholder for future sales-specific configurations
                            ]),

                        Tabs\Tab::make('Inventory')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('weight')
                                        ->label('Weight')
                                        ->numeric(),
                                        
                                    Select::make('product_unit_id')
                                        ->relationship('unit', 'name')
                                        ->label('Unit of Measure')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                        
                                    TextInput::make('strategy')
                                        ->label('Strategy')
                                        ->required()
                                        ->default('MTS'),
                                        
                                    TextInput::make('safety_stock')
                                        ->label('Safety Stock')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                        
                                    TextInput::make('lead_time')
                                        ->label('Lead Time (days)')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                        
                                    Toggle::make('is_active')
                                        ->label('Active')
                                        ->default(true)
                                        ->required(),
                                ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
