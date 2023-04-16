<?php

namespace App\Exports;

use App\Models\Inventaris;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventarisExport implements FromView
{
    public function view(): View
    {
        $data = Inventaris::all();
        return view('inventaris.export.excel', compact('data'));
    }
}
