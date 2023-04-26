<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Notification;
use App\Models\Property;
use App\Models\SubmissionCreditPayment;
use App\Models\SubmissionCreditTransaction;
use App\Models\SubmissionDownPayment;
use App\Models\SubmissionTransferPayment;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
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
                "id" => $item->id,
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

    public function transaction_edit(Transaction $transaction)
    {
        $transaction_detail = TransactionDetail::where('transaction_id', $transaction->id)->get()->first();
        $propertyId = $transaction_detail->property_id;
        $property = Property::where('id', $propertyId)->first();
        $quantity = $transaction_detail->qty;
        $payments = CategoryPayment::all();
        $header_category = $this->category_product;
        $submission = SubmissionCreditTransaction::where('transaction_id', $transaction->id)->get()->first();
        return view('customer.notifications.transaction.transaction_edit', compact('transaction', 'property', 'header_category', 'payments', 'quantity', 'submission'));
    }

    public function transaction_edit_store(Request $request, $transactionId)
    {
        $request->validate(
            [
                'property_id' => 'required|exists:properties,id',
                'quantity_property' => 'required',
                'category_payment_id' => 'required|exists:category_payments,id',
                'recipient_name' => 'required',
                'deliver_to' => 'required',
                'account_number' => 'nullable',
                'evidence_payment' => 'nullable',
                'credit_period' => 'nullable',
                'total' => 'required'
            ],
            [],
            [
                'property_id' => 'Property',
                'quantity_property' => 'Quantity',
                'category_payment_id' => 'Payment Type',
                'recipient_name' => 'Recipient Name',
                'deliver_to' => 'Deliver To',
                'account_number' => 'Account Number',
                'evidence_payment' => 'Approved Payment Customer',
                'credit_period' => 'Credit Periode',
                'down_payment' => $request->down_payment,
                'total' => 'Total'
            ]
        );
        // dd($request, $transactionId);
        DB::beginTransaction();
        try {
            $transaction = Transaction::where('id', $transactionId)->get()->first();
            // dd($transaction);
            $customer = Auth::guard('customer')->user();
            $payment = CategoryPayment::where('id', $request->category_payment_id)->first();
            if ($payment->name == "Cash" || $payment->name == "Cash On Delivery") {
                $transaction->update([
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    'total_payment' => $request->total,
                    'status' => "pending",
                    "latitude" => $request->lat,
                    "longitude" => $request->lng
                ]);
                store_notif($customer->id, "Transaksi Cash On Delivery berhasil diperbarui.", "Transaction");
                $redirect = redirect()->route('customer.notification.index')->with('result', ['success', 'Transaksi Cash On Delivery berhasil diperbarui.']);
            } else if ($payment->name == "Kredit" || $payment->name == "Credit") {
                $total_credit = 0;
                $transactionOnProgress = Transaction::where("customer_id", $customer->id)->where("status", "on_progress")->get();
                foreach ($transactionOnProgress as $transactionOP) {
                    if ($transactionOP->credit_period != null && $transactionOP->credit_period > 0) {
                        $total_credit += $transactionOP->payment_credit;
                    }
                }
                $total_credit += $request->payment_credit;

                if ($total_credit > $customer->salary) {
                    store_notif($customer->id, "Transaksi Anda tidak dapat diproses, karena gaji Anda tidak cukup", "Transaction");
                    return redirect()->back()->with("result", ["error", "Transaksi Anda tidak dapat diproses, karena gaji Anda tidak cukup"]);
                }

                $transaction->update([
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    'total_payment' => $request->total,
                    'credit_period' => $request->credit_period,
                    'payment_credit' => $request->payment_credit,
                    'down_payment' => $request->down_payment,
                    'status' => $request->status,
                    "latitude" => $request->lat,
                    "longitude" => $request->lng
                ]);

                $upload_path = "/upload/transaction/submission_credit/";
                $path = public_path($upload_path);
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                if ($request->file('ktp')->isValid()) {
                    $ktp = $request->file('ktp');
                    $ktp_name = 'ktp_' . Str::random(5) . time() . '.' . $ktp->extension();
                    $k = Image::make($ktp->path());
                    $k->save($path . '' . $ktp_name);
                }
                if ($request->file('salary_slip')->isValid()) {
                    $salary_slip = $request->file('salary_slip');
                    $salary_slip_name = 'salaryslip_' . Str::random(5) . time() . '.' . $salary_slip->extension();
                    $ss = Image::make($salary_slip->path());
                    $ss->save($path . '' . $salary_slip_name);
                }
                if ($request->file('photo')->isValid()) {
                    $photo = $request->file('photo');
                    $photo_name = 'photo_' . Str::random(5) . time() . '.' . $photo->extension();
                    $p = Image::make($photo->path());
                    $p->save($path . '' . $photo_name);
                }
                if ($request->file('house_image')->isValid()) {
                    $house_image = $request->file('house_image');
                    $house_image_name = 'house_image_' . Str::random(5) . time() . '.' . $house_image->extension();
                    $hi = Image::make($house_image->path());
                    $hi->save($path . '' . $house_image_name);
                }
                if ($request->file('transportation_image')->isValid()) {
                    $transportation_image = $request->file('transportation_image');
                    $transportation_image_name = 'transportation_image_' . Str::random(5) . time() . '.' . $transportation_image->extension();
                    $ti = Image::make($transportation_image->path());
                    $ti->save($path . '' . $transportation_image_name);
                }
                if ($request->file('rekening_book_image')->isValid()) {
                    $rekening_book_image = $request->file('rekening_book_image');
                    $rekening_book_image_name = 'rekening_book_image_' . Str::random(5) . time() . '.' . $rekening_book_image->extension();
                    $rbi = Image::make($rekening_book_image->path());
                    $rbi->save($path . '' . $rekening_book_image_name);
                }

                $submissionCT = SubmissionCreditTransaction::where('transaction_id', $transactionId)->get()->first();
                $submissionCT->update([
                    "ktp_name" => $request->ktp_name,
                    "ktp_number" => $request->ktp_number,
                    "ktp_address" => $request->ktp_address,
                    "ktp" => $ktp_name,
                    "salary_slip" => $salary_slip_name,
                    "photo" => $photo_name,
                    "full_name" => $request->full_name,
                    "nickname" => $request->nickname,
                    "mother_name" => $request->mother_name,
                    "post_code" => $request->post_code,
                    "phone" => $request->phone,
                    "birth_place" => $request->birth_place,
                    "birth_date" => $request->birth_date,
                    "gender" => $request->gender,
                    "home_state" => $request->home_state,
                    "long_occupied" => $request->long_occupied,
                    "year_or_month_occupied" => $request->year_or_month_occupied,
                    "education" => $request->education,
                    "marital_status" => $request->marital_status,
                    "jobs" => $request->jobs,
                    "company_name" => $request->company_name,
                    "company_address" => $request->company_address,
                    "company_phone" => $request->company_phone,
                    "length_of_work" => $request->length_of_work,
                    "year_or_month_work" => $request->year_or_month_work,
                    "income_amount" => $request->income_amount,
                    "extra_income" => $request->extra_income,
                    "spending" => $request->spending,
                    "residual_income" => $request->residual_income,
                    "transportation_type" => $request->transportation_type,
                    "transportation_brand" => $request->transportation_brand,
                    "year_of_purchase" => $request->year_of_purchase,
                    "police_number" => $request->police_number,
                    "transportation_color" => $request->transportation_color,
                    "bpkb_number" => $request->bpkb_number,
                    "rekening_number" => $request->rekening_number,
                    "bank" => $request->bank,
                    "owner_rekening" => $request->owner_rekening,
                    "house_image" => $house_image_name,
                    "transportation_image" => $transportation_image_name,
                    "rekening_book_image" => $rekening_book_image_name
                ]);
                store_notif($customer->id, "Data Kredit berhasil diupdate, mohon di cek untuk lebih detailnya", 'Transaction');
                $redirect = redirect()->route('customer.notification.index')->with('result', ['success', 'Data Kredit berhasil diupdate, mohon di cek untuk lebih detailnya']);
            } else {
                $due_date = Carbon::now()->addDay();
                $transaction->update([
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    'total_payment' => $request->total,
                    'status' => "in_progress",
                    'due_date' => $due_date,
                    "latitude" => $request->lat,
                    "longitude" => $request->lng
                ]);
                store_notif($customer->id, "Pembayaran melalui via transfer sedang menunggu untuk konfirmasi", 'Transaction');
                $redirect = redirect()->route('customer.notification.transaction.index')->with('result', ['success', 'Pembayaran melalui via transfer sedang menunggu untuk konfirmasi']);
            }
            $property = Property::where('id', $request->property_id)->first();
            $transactionDetail = TransactionDetail::where('transaction_id', $transactionId)->get()->first();
            $oldTransactionDetail = $transactionDetail;
            $transactionDetail->update([
                'property_id' => $request->property_id,
                'qty' => $request->quantity_property,
                'price' => $property->price,
                'total_price' => $property->price * $request->quantity_property
            ]);
            if ($request->quantity_property > $oldTransactionDetail->qty) {
                $property->update([
                    'stock' => $property->stock - ($request->quantity_property - $oldTransactionDetail->qty)
                ]);
            }
            DB::commit();
            return $redirect;
        } catch (Exception $ex) {
            Log::debug($ex);
            DB::rollback();
            return redirect()->route('landing.index')->with('result', ['error', 'Somethings Error: ' . $ex]);
        }
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
            ],
            [],
            [
                'myimg' => 'Image',
            ]
        );
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
            $notif = store_notif($transaction->customer_id, "Pembayaran Kredit Anda Terkirim", "Submission Credit Payment");
            DB::commit();

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Transaksi pembayaran sukses"]);
        } catch (Exception $e) {
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
            ],
            [],
            [
                'myimg' => 'Image',
            ]
        );
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
            $notif = store_notif($transaction->customer_id, "DP berhasil masuk, mohon menunggu konfirmasi dari admin", "Submission Down Payment");
            DB::commit();

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Success paying transaction"]);
        } catch (Exception $e) {
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
            ],
            [],
            [
                'myimg' => 'Image',
            ]
        );
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

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Transaksi pembayaran sukses"]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->route("customer.notification.transaction.index")->with("result", ["error", "Failed"]);
        }
    }
}
