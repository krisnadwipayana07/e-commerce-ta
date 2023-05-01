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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::with(['category_payment', 'customer'])->where('status', 'paid')->orderBy('created_at', 'ASC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('category_payment_id', function ($data) {
                    return $data->category_payment->name;
                })
                ->editColumn('customer_id', function ($data) {
                    return $data->customer->name;
                })
                ->editColumn('status', function ($data) {
                    return returnStatusOrder($data->status);
                })
                ->editColumn('total_payment', function ($data) {
                    return format_rupiah($data->total_payment);
                })
                ->addColumn('phone', function ($data) {
                    return $data->account_number;
                })
                ->addColumn('action', function ($data) {
                    return onlyShowBtn('Transaction', route('admin.transaction.show', $data->id)) . onlyDeleteBtn('Transaction', route('admin.transaction.destroy', $data->id), route('admin.transaction.index'));
                })
                ->rawColumns(['phone', 'action'])
                ->make(true);
        }
        // $category_payment = CategoryPayment::where('status','active')->orderBy('name', 'ASC')->get();
        // $category_property = CategoryProperty::where('status','active')
        //     ->with([
        //         'property' => function($q){
        //             return $q->where('status','active')->get();
        //         }
        //     ])
        //     ->orderBy('name', 'ASC')
        //     ->get();
        return view('admin.transaction.index');
    }

    public function create()
    {
        $data_customer = Customer::orderBy('name', 'ASC')->get();
        $category_payment = CategoryPayment::where('status', 'active')->orderBy('name', 'ASC')->get();
        $category_property = CategoryProperty::where('status', 'active')
            ->with([
                'sub_category_property' => function ($q) {
                    return $q->where('status', 'active')
                        ->with([
                            'property' => function ($q2) {
                                return $q2->where('status', 'active')->get();
                            }
                        ])->get();
                }
            ])
            ->orderBy('name', 'ASC')
            ->get();
        return view('admin.transaction.create', compact('category_property', 'category_payment', 'data_customer'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'customer_id' => 'required',
                'category_payment_id' => 'required',
                'recipient_name' => 'required',
                'deliver_to' => 'required',
                'total_payment' => 'required',
                'choose_property_id.*' => 'required',
                'choose_property_qty.*' => 'required',
                'choose_property_price.*' => 'required',
            ],
            [],
            [
                'customer_id' => 'Customer',
                'category_payment_id' => 'Category Payment',
                'recipient_name' => 'Recipient Name',
                'deliver_to' => 'Deliver To',
                'total_payment' => 'Total Payment',
                'choose_property_id.*' => 'Property',
                'choose_property_qty.*' => 'Qty',
                'choose_property_price.*' => 'Price',
            ]
        );

        DB::beginTransaction();
        try {
            $request['admin_id'] = auth()->guard('admin')->user()->id;
            $transaction = Transaction::create($request->all());
            do {
                $code = generateOrderNumber($transaction->id);
                $check_transaction = Transaction::where('code', $code)->first();
            } while ($check_transaction);
            $transaction->update(['code' => $code]);

            // Transaction Detail
            foreach ($request->choose_property_id as $key => $item) {
                if ($request->choose_property_qty[$key] > 0) {
                    $transactionDetail = TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'property_id' => $item,
                        'qty' => $request->choose_property_qty[$key],
                        'price' => $request->choose_property_price[$key],
                        'total_price' => (int)$request->choose_property_price[$key] * (int)$request->choose_property_qty[$key],
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #' . $transaction->code . ' Added Successfully.']);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transaction.show', ['data' => $transaction]);
    }

    public function edit(Transaction $transaction)
    {
        $category_payment = CategoryPayment::where('status', 'active')->orderBy('name', 'ASC')->get();
        $category_property = CategoryProperty::where('status', 'active')
            ->with([
                'sub_category_property' => function ($q) {
                    return $q->where('status', 'active')
                        ->with([
                            'property' => function ($q2) {
                                return $q2->where('status', 'active')->get();
                            }
                        ])->get();
                }
            ])
            ->orderBy('name', 'ASC')
            ->get();
        return view('admin.transaction.edit', ['data' => $transaction, 'category_payment' => $category_payment, 'category_property' => $category_property]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate(
            [
                'status' => 'required',
                'myimg' => 'nullable|mimes:jpeg,png|max:2048',
            ],
            [],
            [
                'status' => 'Status',
                'myimg' => 'Image',
            ]
        );

        DB::beginTransaction();
        try {
            if ($request->hasFile('myimg')) {
                $request['img'] = updateImg('upload/admin/transaction/', $transaction->img);
            }

            $transaction->update($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #' . $transaction->code . ' Updated Successfully.']);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        DB::beginTransaction();
        try {

            // deleteImg('upload/admin/transaction/', $transaction->img);

            $transaction->delete();
            DB::commit();
            $result = 'Data #' . $transaction->name . ' Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function evidence_payment_index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::join('category_payments as cp', 'transactions.category_payment_id', '=', 'cp.id')
                ->select('transactions.code', 'transactions.id', 'transactions.account_number', 'transactions.recipient_name', 'cp.name', 'transactions.status')
                ->whereIn('transactions.status', ['pending', 'in_progress'])
                ->orderBy('transactions.created_at', 'ASC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return onlyShowBtn('Approved Payment Customer', route('admin.evidence_payment.show', $data->id));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.evidence_payment.index');
    }

    public function evidence_payment_show(Transaction $transaction)
    {
        $submission_credit_transactions = null;
        if ($transaction->credit_period != null && $transaction->credit_period > 0) {
            $submission_credit_transactions = SubmissionCreditTransaction::where('transaction_id', $transaction->id)->first();
        }
        return view('admin.evidence_payment.show', ['data' => $transaction, 'submission' => $submission_credit_transactions]);
    }

    public function evidence_payment_approve(Request $request, Transaction $transaction)
    {
        $request->validate(
            [
                'status' => 'required',
            ],
            [],
            [
                'status' => 'Status',
            ]
        );

        DB::beginTransaction();
        try {
            $transaction->update([
                'status' => $request->status
            ]);
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            foreach ($transactionDetails as $transactionDetail) {
                $property = Property::where('id', $transactionDetail->property_id)->first();
                $property->update([
                    'stock' => $property->stock - $transactionDetail->qty
                ]);
            }
            // Delivery::make($transaction->customer_id, $transaction->id, Delivery::STATUS_IN_TRANSIT);
            DB::commit();
            return redirect()->route('admin.evidence_payment.index')->with('result', ['success', 'Approve transaction']);
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('result', ['error', 'Something error: ' . $ex]);
        }
    }

    public function evidence_payment_reject(Transaction $transaction, Request $request)
    {
        DB::beginTransaction();
        try {
            // $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            // foreach ($transactionDetails as $transactionDetail) {
            //     $transactionDetail->delete();
            // }
            // $transaction->delete();
            $action = $transaction->update([
                'status' => 'reject'
            ]);
            if ($action) {
                $message = $request->has('message') ? $request->message : "Your Credit Payment Submission has been Rejected! Please review your submission!";
                store_notif($transaction->customer_id, $message, "Transaction");
            }
            restore_property_stocks($transaction->id);
            Delivery::make($transaction->customer_id, $transaction->id, Delivery::STATUS_REJECTED);
            DB::commit();
            return redirect()->route('admin.evidence_payment.index');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('result', ['error', 'Something error: ' . $ex]);
        }
    }

    public function evidence_payment_notify_user(Transaction $transaction, Request $request)
    {
        try {
            store_notif($transaction->customer_id, $request->message ?: "Something wrong in your transactions! Please check your transactions", $request->type, $request->transaction_id);
            return redirect()->back()->with('result', ['success', 'Notifications has been sent!']);
        } catch (Exception $ex) {
            return redirect()->back()->with('result', ['error', 'Something error: ' . $ex]);
        }
    }
}
