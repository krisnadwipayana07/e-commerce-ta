<?php

namespace App\Exports;

use App\Models\Admin;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdminExport implements FromView
{
    public function view(): View
    {
        $data = Admin::all();
        return view('admin.export.excel', compact('data'));
    }
}
