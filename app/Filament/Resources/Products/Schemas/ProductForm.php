<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required(),
                TextInput::make('code_barang')
                    ->label('Kode Barang')
                    ->disabled()
                    ->dehydrated(false) 
                    ->hidden(),
                TextInput::make('price')
                    ->label('Harga Produk')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('unit_id')
                    ->label('Satuan')
                    ->relationship('unit', 'name')
                    ->required(),
                TextInput::make('stock')
                    ->label('Stok Produk')
                    ->numeric()
                    ->default(null),
                FileUpload::make('foto_produk')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
