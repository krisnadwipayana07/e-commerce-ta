<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Notification;
use App\Models\SubmissionCreditPayment;
use App\Models\SubmissionDownPayment;
use App\Models\SubmissionTransferPayment;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    protected $category_product;
    protected $statuses = [
        'paid' => 'PAID',
        'in_progress' => 'IN PROGRESS',
        'pending' => 'PENDING',
        'reject' => 'REJECTED'
    ];
    protected $buttons = [
        'paid' => 'success',
        'in_progress' => 'warning',
        'pending' => 'info',
        'reject' => 'danger'
    ];

    public function __construct()
    {
        $this->category_product = CategoryProperty::where('status', 'active')->get();
    }

    public function index()
    {
        $header_category = $this->category_product;
        $notifications = Notification::where("customer_id", Auth::guard("customer")->user()->id)->orderBy('created_at', 'desc')->get();
        return view('customer.notifications.index', compact('header_category', 'notifications'));
    }
    
    public function show(Notification $notification)
    {
        $notification->update([
            "is_read" => true
        ]);
        return view('customer.notifications.show', compact('notification'));
    }


    // Transaction
    public function transaction_index(Request $request)
    {
        // if($request->ajax()){
        //     $customer = Auth::guard('customer')->user();
        //     $data = Transaction::with(['category_payment'])
        //             ->where('customer_id', $customer->id)
        //             ->where('status', 'paid')
        //             ->orderBy('created_at', 'ASC')
        //             ->get();
        //     return DataTables::of($data)
        //             ->addIndexColumn()
        //             ->editColumn('total_payment', function($data) {
        //                 return format_rupiah($data->total_payment);
        //             })
        //             ->editColumn('category_payment_id', function($data) {
        //                 return $data->category_payment->name;
        //             })
        //             ->addColumn('action', function($data){
        //                 return onlyShowBtnRedirect(route('customer.notification.transaction.show', $data->id));
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
        $customer = Auth::guard('customer')->user();
        $status = ['paid', 'in_progress', 'pending', 'reject'];
        $data = Transaction::with(['category_payment'])
                ->where('customer_id', $customer->id)
                ->whereIn('status', $status)
                ->orderBy('created_at', 'DESC')
                ->get();
        $transactions = [];
        foreach ($data as $item) {
            $detail_transactions = TransactionDetail::with(['property'])->where('transaction_id', $item->id)->get();
            $isCredit = $item->category_payment->name == "Credit" || $item->category_payment->name == "Kredit" ? true : false;
            $isTransfer = str_contains(strtoupper($item->category_payment->name), 'TRANSFER');
            $submission_credit_payment = new SubmissionCreditPayment();
            $count_submission_credit_payment = 0;
            if ($isCredit) {
                $submission_credit_payment = SubmissionCreditPayment::where("transaction_id", $item->id)->where("status", "accept");
                $count_submission_credit_payment = $submission_credit_payment->count();
                $submission_credit_payment = $submission_credit_payment->get();
            }
            $transaction_detail = [];
            foreach ($detail_transactions as $detail) {
                $transaction_detail[] = [
                    "img" => $detail->property->img,
                    "property" => $detail->property->name,
                    "price" => format_rupiah($detail->property->price)
                ];
            }
            $remaining_payment = $item->total_payment - ($item->down_payment + $item->payment_credit * $count_submission_credit_payment);
            $remaining_payment = $remaining_payment < 0 ? 0 : $remaining_payment;
            $transactions[] = [
                "code" => $item->code,
                "detail" => $transaction_detail,
                "payment" => $item->category_payment->name,
                "total_payment" => format_rupiah($item->total_payment),
                "credit_period" => $isCredit ? $item->credit_period : null,
                "credit_payment" => $isCredit ? format_rupiah($item->payment_credit) : null,
                "remaining_payment" => $isCredit ? format_rupiah($remaining_payment) : null,
                "remaining_instalment" => $isCredit ? ($item->credit_period - $item->total_phase) : null,
                "down_payment" => $isCredit ? format_rupiah($item->down_payment) : null,
                "isDP" => $item->is_dp_paid,
                "status" => $this->statuses[$item->status],
                "button" => $this->buttons[$item->status],
                "isCredit" => $isCredit,
                "route" => route('customer.credit.payment.index', ['transaction' => $item->id]),
                "routeDP" => route('customer.dp.payment.index', ['transaction' => $item->id]),
                "isTransfer" => $isTransfer,
                "due_date" => $item->due_date,
                "routeTransfer" => route('customer.transfer.payment.index', ['transaction' => $item->id])
            ];
        }
        $header_category = $this->category_product;

        $payments = CategoryPayment::all();
        return view('customer.notifications.transaction.index', compact('header_category', 'transactions', 'payments'));
    }

    public function transaction_show(Request $request, Transaction $transaction)
    {
        $header_category = $this->category_product;
        $transaction = Transaction::with(['category_payment'])->find($transaction->id);  
        $detail_transactions = TransactionDetail::with(['property'])->where('transaction_id', $transaction->id)->get();
        $isCredit = $transaction->category_payment->name == "Credit" || $transaction->category_payment->name == "Kredit" ? true : false;
        $submission_credit_payment = new SubmissionCreditPayment();
        $count_submission_credit_payment = 0;
        if ($isCredit) {
            $submission_credit_payment = SubmissionCreditPayment::where("transaction_id", $transaction->id)->where("status", "accept");
            $count_submission_credit_payment = $submission_credit_payment->count();
            $submission_credit_payment = $submission_credit_payment->get();
        }
        return view('customer.notifications.transaction.show', compact('header_category', 'transaction', 'detail_transactions', 'submission_credit_payment', 'count_submission_credit_payment', 'isCredit'));
    }
    
    public function credit_payment_index(Transaction $transaction)
    {
        return view("customer.notifications.transaction.credit_payment", compact("transaction"));
    }
    
    public function dp_payment_index(Transaction $transaction)
    {
        return view("customer.notifications.transaction.down_payment", compact("transaction"));
    }
    
    public function credit_payment_store(Request $request, Transaction $transaction)
    {
        $request->validate(
        [
            'myimg' => 'required',
        ], [],
        [
            'myimg' => 'Image',
        ]);
        DB::beginTransaction();
        try {
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/customer/submission_credit_payment/');
            }

            $total_phase = $transaction->total_phase ?? 0;
            $payment_phase = $total_phase + 1;

            SubmissionCreditPayment::create([
                "transaction_id" => $transaction->id,
                "customer_id" => $transaction->customer_id,
                "payment_phase" => $payment_phase,
                "payment" => $transaction->payment_credit,
                "evidence_payment" => $img_name,
                "status" => "pending"
            ]);
            $notif = store_notif($transaction->customer_id, "Your Credit Payment Sended", "Submission Credit Payment");
            DB::commit();

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Success paying transaction"]);
        } catch(Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->route("customer.notification.transaction.index")->with("result", ["error", "Failed"]);
        }
    }
    
    public function dp_payment_store(Request $request, Transaction $transaction)
    {
        $request->validate(
        [
            'myimg' => 'required',
        ], [],
        [
            'myimg' => 'Image',
        ]);
        DB::beginTransaction();
        try {
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/customer/submission_dp_payment/');
            }

            $total_phase = $transaction->total_phase ?? 0;
            $payment_phase = $total_phase + 1;

            SubmissionDownPayment::create([
                "transaction_id" => $transaction->id,
                "customer_id" => $transaction->customer_id,
                "payment_phase" => $payment_phase,
                "payment" => $transaction->payment_credit,
                "evidence_payment" => $img_name,
                "status" => "pending"
            ]);
            $notif = store_notif($transaction->customer_id, "Your Down Payment Sended", "Submission Down Payment");
            DB::commit();

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Success paying transaction"]);
        } catch(Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->route("customer.notification.transaction.index")->with("result", ["error", "Failed"]);
        }
    }
    
    public function transfer_payment_index(Transaction $transaction)
    {
        return view("customer.notifications.transaction.transfer_payment", compact("transaction"));
    }
    
    public function transfer_payment_store(Request $request, Transaction $transaction)
    {
        $request->validate(
        [
            'myimg' => 'required',
        ], [],
        [
            'myimg' => 'Image',
        ]);
        DB::beginTransaction();
        try {
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/customer/submission_transfer_payment/');
            }

            $total_phase = $transaction->total_phase ?? 0;
            $payment_phase = $total_phase + 1;

            SubmissionTransferPayment::create([
                "transaction_id" => $transaction->id,
                "customer_id" => $transaction->customer_id,
                "evidence_payment" => $img_name,
                "status" => "pending"
            ]);

            DB::commit();

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Success paying transaction"]);
        } catch(Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->route("customer.notification.transaction.index")->with("result", ["error", "Failed"]);
        }
    }
}
