<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Property;
use App\Models\SubmissionCreditTransaction;
use App\Models\SubmissionDeliveryPayment;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeliveryController extends Controller
{
    protected $delivery_status = [
        'Order Received' => 'Pesanan Dibuat',
        'In Transit' => 'Pesanan Dalam Pengiriman',
        'Delivered' => 'Pesanan Telah Diterima',
        'Rejected' => 'Ditolak'
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            $transaction = Transaction::whereIn('status', ['paid', 'in_progress'])
                ->orderBy('updated_at')
                ->get();
            // $delivery = Delivery::join('transactions as trx', 'deliveries.transaction_id', '=', 'trx.id')
            //     ->select('trx.code', 'deliveries.transaction_id')
            //     ->groupBy('deliveries.transaction_id')
            //     ->get();
            foreach ($transaction as $temp) {
                $status = '';

                $isCredit = $temp->category_payment->name == "Credit" || $temp->category_payment->name == "Kredit" ? true : false;
                $isCOD = $temp->category_payment->name == "Cash On Delivery" ? true : false;
                $isTransfer = str_contains(strtoupper($temp->category_payment->name), 'TRANSFER');
                $temp_status = Delivery::where('deliveries.transaction_id', $temp->id)->orderBy('created_at', 'DESC')->first();
                if ($temp_status) {
                    $status = $this->delivery_status[$temp_status->status];
                }

                if (($isCredit && $temp->is_dp_paid) || ($isTransfer && $temp->status == "paid") || ($isCOD)) {
                    $data[] = [
                        'id' => $temp->id,
                        'code' => $temp->code,
                        'status' => $status,
                    ];
                }
                // if ($isCredit && $temp->is_dp_paid) {
                //     $data[] = [
                //         'id' => $temp->id,
                //         'code' => $temp->code,
                //         'status' => $status,
                //     ];
                // }
                // if ($isTransfer && $temp->status == "paid") {
                //     $data[] = [
                //         'id' => $temp->id,
                //         'code' => $temp->code,
                //         'status' => $status,
                //     ];
                // }
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
        $status = '';
        $delivery = Delivery::where('deliveries.transaction_id', $transaction->id)->orderBy('created_at', 'DESC')->first();
        $evidence = SubmissionDeliveryPayment::where('transaction_id', $transaction->id)->orderBy('created_at', 'DESC')->first();
        if ($delivery != null) {
            $status = $delivery->status;
        }
        $submission_credit_transactions = null;
        if ($transaction->credit_period != null && $transaction->credit_period > 0) {
            $submission_credit_transactions = SubmissionCreditTransaction::where('transaction_id', $transaction->id)->first();
        }
        return view('admin.delivery.show', ['data' => $transaction, 'submission' => $submission_credit_transactions, 'status' => $status, 'evidence' => $evidence]);
    }

    public function store(Request $request)
    {
        try {
            Delivery::make($request->customer_id, $request->transaction_id, $request->status);
            if ($request->payment_type == "Cash On Delivery" && $request->status == "Delivered") {
                $transaction = Transaction::where('id', $request->transaction_id)->get()->first();
                $transaction->update([
                    'status' => 'paid',
                ]);
            }
            return redirect()->back()->with('result', ['success', 'Status has been save!']);
        } catch (Exception $ex) {
            return redirect()->back()->with('result', ['error', 'Something error: ' . $ex]);
        }
    }

    public function delivery_evidence_index(Request $request)
    {
        // $data = Transaction::with(['category_payment'])
        //     ->leftJoin(DB::raw('(SELECT transaction_id, SUBSTRING(MAX(CONCAT(created_at, ": ", status)), 22) as status, created_at FROM deliveries GROUP BY transaction_id ORDER BY created_at DESC) AS latest_deliveries'), function ($join) {
        //         $join->on('transactions.id', '=', 'latest_deliveries.transaction_id');
        //     })
        //     ->where('latest_deliveries.status', Delivery::STATUS_IN_TRANSIT)
        //     ->orderBy('transactions.created_at', 'DESC')
        //     ->orderBy('latest_deliveries.created_at', 'desc')
        //     ->groupBy('transactions.id')
        //     ->get(['transactions.id', 'transactions.code', 'transactions.recipient_name', 'transactions.deliver_to', 'latest_deliveries.status as delivery_status']);
        // dd($data);
        if ($request->ajax()) {
            $data = Transaction::with(['category_payment'])
                ->leftJoin(DB::raw('(SELECT transaction_id, SUBSTRING(MAX(CONCAT(created_at, ": ", status)), 22) as status, created_at, updated_at FROM deliveries GROUP BY transaction_id ORDER BY created_at DESC) AS latest_deliveries'), function ($join) {
                    $join->on('transactions.id', '=', 'latest_deliveries.transaction_id');
                })
                ->where('latest_deliveries.status', Delivery::STATUS_IN_TRANSIT)
                ->orderBy('latest_deliveries.updated_at', 'desc')
                ->groupBy('transactions.id')
                ->get(['transactions.id', 'transactions.code', 'transactions.recipient_name', 'transactions.deliver_to', 'latest_deliveries.status as delivery_status']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('customer_name', function ($data) {
                    return $data->recipient_name;
                })
                ->editColumn('address', function ($data) {
                    return $data->deliver_to;
                })
                ->addColumn('action', function ($data) {
                    return onlyShowBtn('Delivery Show', route('admin.delivery.evidence.show', $data->id));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.delivery.evidence.index');
    }

    public function delivery_evidence_show(Transaction $transaction)
    {
        $evidence = SubmissionDeliveryPayment::where('transaction_id', $transaction->id)->orderBy('created_at', 'DESC')->first();
        return view('admin.delivery.evidence.show', compact('transaction', 'evidence'));
    }

    public function delivery_evidence_store(Request $request, Transaction $transaction)
    {
        try {
            if ($request->hasFile('product_evidence')) {
                $productFile = updateImage('upload/admin/delivery/product/', $transaction->id, 'product_evidence');
            }
            // dd($productFile);
            if ($request->hasFile('signature_evidence')) {
                $signatureFile = updateImage('upload/admin/delivery/signature/', $transaction->id, 'signature_evidence');
            }
            SubmissionDeliveryPayment::create([
                'transaction_id' => $transaction->id,
                'product_evidence' => $productFile,
                'signature_evidence' => $signatureFile
            ]);
            return redirect()->back()->with('result', ['success', 'New delivery data has been save!']);
        } catch (Exception $ex) {
            return redirect()->back()->with('result', ['error', 'Something error: ' . $ex]);
        }
    }
}
