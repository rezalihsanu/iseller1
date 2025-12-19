<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function creating(Product $product)
    {
        if (!$product->category_id || !$product->name) {
            return;
        }

        // 3 huruf kategori
        $categoryCode = strtoupper(substr($product->category->name, 0, 3));

        // 3 huruf nama produk (tanpa spasi & simbol)
        $nameCode = strtoupper(substr(
            preg_replace('/[^A-Za-z]/', '', $product->name),
            0,
            3
        ));

        $nameCode = str_pad($nameCode, 3, 'X');

        // PREFIX = CAT + NAME
        $prefix = $categoryCode . $nameCode;

        // Cari nomor terakhir dari semua produk (GLOBAL)
        $last = Product::orderBy('id', 'DESC')->first();

        if ($last) {
            // Ambil nomor 3 digit terakhir saja
            preg_match('/(\d{3})$/', $last->code_barang, $matches);

            $lastNumber = isset($matches[1]) ? (int) $matches[1] : 0;
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        // Final code
        $product->code_barang = $prefix . $nextNumber;
    }
}
