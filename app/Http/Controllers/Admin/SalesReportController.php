<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Transaction::with(['customer', 'transaction_detail', 'category_payment'])->where('status', 'paid')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('property_name', function($data) {
                        $text = "<ul>";
                        foreach($data->transaction_detail as $item) {
                            $text .= "<li>" . $item->property->name . "</li>"; 
                        }
                        $text .= "</ul>";
                        return $text;
                    })
                    ->addColumn('customer_name', function($data) {
                        return $data->customer->name;
                    })
                    ->addColumn("payment_method", function($data) {
                        return $data->category_payment->name;
                    })
                    ->editColumn('total_payment', function($data) {
                        return format_rupiah($data->total_payment);
                    })
                    ->rawColumns(['property_name', 'customer_name', 'payment_method'])
                    ->make(true);
        }
        return view('admin.report.sales.index');
    }

    public function print()
    {
        $data = Transaction::with(['customer', 'transaction_detail', 'category_payment'])->where('status', 'paid')->get();
        
        $pdf = PDF::loadview('admin.report.sales.print', ["data" => $data]);
        return $pdf->download('report-sales.pdf');
    }
}
