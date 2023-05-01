<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Property;
use App\Models\SubmissionCreditTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            $transaction = Transaction::where('status', 'paid')->get();
            // $delivery = Delivery::join('transactions as trx', 'deliveries.transaction_id', '=', 'trx.id')
            //     ->select('trx.code', 'deliveries.transaction_id')
            //     ->groupBy('deliveries.transaction_id')
            //     ->get();
            foreach ($transaction as $temp) {
                $status = '';

                $temp_status = Delivery::where('deliveries.transaction_id', $temp->id)->orderBy('created_at', 'DESC')->first();
                if ($temp_status) {
                    $status = $temp_status->status;
                }

                $data[] = [
                    'id' => $temp->id,
                    'code' => $temp->code,
                    'status' => $status,
                ];
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return onlyShowBtn('Delivery Product Show', route('admin.delivery.show', $data['id']));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.delivery.index');
    }

    public function show(Transaction $transaction)
    {

        $submission_credit_transactions = null;
        if ($transaction->credit_period != null && $transaction->credit_period > 0) {
            $submission_credit_transactions = SubmissionCreditTransaction::where('transaction_id', $transaction->id)->first();
        }
        return view('admin.delivery.show', ['data' => $transaction, 'submission' => $submission_credit_transactions]);
    }

    public function store(Request $request)
    {
        try {
            Delivery::make($request->customer_id, $request->transaction_id, $request->status);
            return redirect()->back()->with('result', ['success', 'Status has been save!']);
        } catch (Exception $ex) {
            return redirect()->back()->with('result', ['error', 'Something error: ' . $ex]);
        }
    }
}
