<?php

namespace App\Exports;

use App\Models\BahanBaku;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BahanBakuExport implements FromView
{
    public function view(): View
    {
        $data = BahanBaku::all();
        return view('bahan_baku.export.excel', compact('data'));
    }
}
