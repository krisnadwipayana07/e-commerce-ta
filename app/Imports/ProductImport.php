<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $data = Product::create([
                'name' => $row['name'],
                'desc' => $row['desc'],
                'purchase_price' => $row['purchase_price'],
                'sell_price' => $row['sell_price'],
                'size' => $row['size'],
                'stock' => $row['stock'],
                
            ]);
            $digit = generateDigit($data->id);
            $code = 'Pro' . $digit;
            $data->update(['code' => $code]);
        }
    }
}
