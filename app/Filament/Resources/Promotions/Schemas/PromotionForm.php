<?php

namespace App\Filament\Resources\Promotions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(['percentage' => 'Percentage', 'nominal' => 'Nominal'])
                    ->default('percentage')
                    ->required(),
                TextInput::make('discount_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount_nominal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
