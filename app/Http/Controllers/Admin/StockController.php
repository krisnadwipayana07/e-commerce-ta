<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stock = Stock::join('properties', 'properties.id', 'stocks.property_id')
                    ->select('stocks.id', 'properties.name', 'stocks.stock')->get();
        return view('admin.stock.index', [
            'stock' => $stock
        ]);
    }

    public function edit(Stock $id)
    {
        return view('admin.stock.edit', compact($id));
    }
}
