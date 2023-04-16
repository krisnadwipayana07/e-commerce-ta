<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransactionExport implements FromView
{
    protected $start_date;
    protected $end_date;

    function __construct($start_date, $end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $data = Transaction::whereDate('created_at', '>=', $this->start_date)
                                ->whereDate('created_at', '<=', $this->end_date)
                                ->with(['package', 'transaction_item_detail', 'transaction_extra_adult_detail'])->get();
        return view('admin.transaction.export.excel', compact('data'));
    }
}
