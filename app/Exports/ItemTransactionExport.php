<?php

namespace App\Exports;

use App\Models\ItemTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ItemTransactionExport implements FromView
{
    protected $start_date;
    protected $end_date;

    function __construct($start_date, $end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $data = ItemTransaction::whereDate('created_at', '>=', $this->start_date)
                                ->whereDate('created_at', '<=', $this->end_date)
                                ->orderBy('created_at', 'DESC')
                                ->with(['item'])->get();
        return view('admin.item_transaction.export.excel', compact('data'));
    }
}
