<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Delivery;
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
    protected $status_transaction = [
        'paid' => 'LUNAS',
        'in_progress' => 'Sudah Diterima dan Dalam proses',
        'non_active' => "Tidak Aktif",
        'pending' => 'Sedang Diproses',
        'reject' => 'Ditolak'
    ];
    protected $statuses = [
        'paid' => 'LUNAS',
        'non_active' => "Tidak Aktif",
        'in_progress' => 'DATA PENGAJUAN KREDIT DITERIMA',
        'pending' => 'Data Pengajuan Kredit Sedang Diperiksa',
        'reject' => 'Ditolak'
    ];
    protected $buttons = [
        'paid' => 'success',
        'non_active' => 'danger',
        'in_progress' => 'warning',
        'pending' => 'warning',
        'reject' => 'danger'
    ];
    protected $delivery_status = [
        Delivery::STATUS_ORDER_RECEIVED => 'Pesanan Dibuat',
        Delivery::STATUS_IN_PACKING => 'Pesanan Dalam Pengemasan',
        Delivery::STATUS_IN_TRANSIT => 'Pesanan Dalam Pengiriman',
        Delivery::STATUS_DELIVERED => 'Pesanan Telah Diterima',
        Delivery::STATUS_REJECTED => 'Ditolak'
    ];

    public function __construct()
    {
        $this->category_product = CategoryProperty::where('status', 'active')->get();
    }

    public function index()
    {
        $header_category = $this->category_product;
        $notifications = Notification::leftJoin('transactions', 'transactions.id', '=', 'notifications.transaction_id')
            ->select('notifications.id', 'notifications.type', 'notifications.message', 'notifications.reply', 'notifications.is_read', 'notifications.transaction_id', 'transactions.status', 'notifications.created_at')
            ->where("notifications.customer_id", Auth::guard("customer")->user()->id)
            ->orderBy('notifications.created_at', 'desc')
            ->get();
        return view('customer.notifications.index', compact('header_category', 'notifications'));
    }

    public function show(Notification $notification)
    {
        $notification->update([
            "is_read" => true
        ]);
        return view('customer.notifications.show', compact('notification'));
    }

    public function reply(Request $request)
    {
        try {
            $notification = Notification::find($request->id);
            $notification->update([
                "reply" => $request->reply
            ]);
            return redirect()->back()->with('result', ['success', 'Balasan sudah dikirim!']);
        } catch (Exception $ex) {
            return redirect()->back()->with('result', ['error', 'Something error: ' . $ex]);
        }
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
        DB::statement("SET SQL_MODE=''");
        $data = Transaction::with(['category_payment'])
            ->leftJoin(DB::raw('(SELECT transaction_id, SUBSTRING(MAX(CONCAT(created_at,": ",status)),22) as status, created_at FROM deliveries GROUP BY transaction_id ORDER BY created_at DESC) AS latest_deliveries'), function ($join) {
                $join->on('transactions.id', '=', 'latest_deliveries.transaction_id');
            })
            ->where('transactions.customer_id', $customer->id)
            ->when($request->filter === 'payment', function ($query) {
                return $query->whereIn('transactions.status', ['in_progress', 'pending']);
            })
            ->when($request->filter === 'paid', function ($query) {
                return $query->whereIn('transactions.status', ['paid']);
            })
            ->when($request->filter === 'non_active', function ($query) {
                return $query->whereIn('transactions.status', ['non_active']);
            })
            ->when($request->filter === 'rejected', function ($query) {
                return $query->whereIn('transactions.status', ['reject']);
            })
            ->when($request->filter === 'in_packing', function ($query) {
                return $query->whereIn('latest_deliveries.status', [Delivery::STATUS_IN_PACKING]);
            })
            ->when($request->filter === 'in_transit', function ($query) {
                return $query->whereIn('latest_deliveries.status', [Delivery::STATUS_IN_TRANSIT]);
            })
            ->when($request->filter === 'delivered', function ($query) {
                return $query->whereIn('latest_deliveries.status', [Delivery::STATUS_DELIVERED]);
            })
            ->orderBy('transactions.created_at', 'DESC')
            ->orderBy('latest_deliveries.created_at', 'desc')
            ->groupBy('transactions.id')
            ->get(['transactions.*', 'latest_deliveries.status as delivery_status']);
        $transactions = [];
        foreach ($data as $item) {
            $detail_transactions = TransactionDetail::with(['property'])->where('transaction_id', $item->id)->get();
            $isCredit = $item->category_payment->name == "Credit" || $item->category_payment->name == "Kredit" ? true : false;
            $isCOD = $item->category_payment->name == "Cash On Delivery" ? true : false;
            $isTransfer = str_contains(strtoupper($item->category_payment->name), 'TRANSFER');
            $submission_credit_payment = new SubmissionCreditPayment();
            $notifications = new Notification();
            $delivery = new Delivery();
            $notif = [];
            $count_submission_credit_payment = 0;
            $approve_latest_payment = "";
            $approve_latest_DP = "";
            $approve_latest_transfer = "";
            $overPrice = 0;

            if ($item->status == "pending") {
                $notifications = Notification::where("transaction_id", $item->id)->get();
                foreach ($notifications as $data) {
                    $notif[] = [
                        'message' => $data->message
                    ];
                }
            }

            $transaction_detail = [];
            foreach ($detail_transactions as $detail) {
                $transaction_detail[] = [
                    "img" => $detail->property->img,
                    "property" => $detail->property->name,
                    "price" => format_rupiah($detail->property->price)
                ];
            }

            if ($isTransfer) {
                $latest_submission_transfer = SubmissionTransferPayment::where("transaction_id", $item->id)->orderBy('created_at', 'DESC')->get()->first();
                if ($latest_submission_transfer) {
                    $approve_latest_transfer = $latest_submission_transfer->status;
                }
            }

            if ($isCredit) {
                $currentDate = Carbon::now();
                $submission_credit_payment = SubmissionCreditPayment::where("transaction_id", $item->id)->orderBy('created_at', 'DESC');
                $latest_payment = $submission_credit_payment->get()->first();
                if ($latest_payment) {
                    $approve_latest_payment = $latest_payment->status;
                }
                $count_submission_credit_payment = $submission_credit_payment->where("status", "accept")->count();
                $submission_credit_payment = $submission_credit_payment->get();
                if ($item->is_dp_paid == 0) {
                    $latest_submission_DP = SubmissionDownPayment::where("transaction_id", $item->id)->orderBy('created_at', 'DESC')->get()->first();
                    if ($latest_submission_DP) {
                        $approve_latest_DP = $latest_submission_DP->status;
                    }
                } else if ($currentDate->gt($item->due_date)) {
                    $overdate = $currentDate->diffInDays($item->due_date);
                    $overPrice = $overdate * ($item->total_payment * 0.02);
                }
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
                "statuses" => $item->status,
                "status" => $isCredit ? $this->statuses[$item->status] : $this->status_transaction[$item->status],
                "button" => $this->buttons[$item->status],
                "isCredit" => $isCredit,
                "isCOD" => $isCOD,
                "route" => route('customer.credit.payment.index', ['transaction' => $item->id]),
                "routeDP" => route('customer.dp.payment.index', ['transaction' => $item->id]),
                "isTransfer" => $isTransfer,
                "due_date" => $item->due_date,
                "message" => $notif,
                "overprice" => format_rupiah($overPrice),
                "latestPayment" => $approve_latest_payment,
                "statusDP" => $approve_latest_DP,
                "statusTransfer" => $approve_latest_transfer,
                'delivery' => array_key_exists($item->delivery_status, $this->delivery_status) ? $this->delivery_status[$item->delivery_status] : "Tidak Ditemukan",
                "routeTransfer" => route('customer.transfer.payment.index', ['transaction' => $item->id])
            ];
        }
        $header_category = $this->category_product;

        $payments = CategoryPayment::all();
        return view('customer.notifications.transaction.index', compact('header_category', 'transactions', 'payments', isset($notifications) ? 'notifications' : null));
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
        DB::beginTransaction();
        try {
            $transaction = Transaction::where('id', $transactionId)->get()->first();
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
                store_notif($customer->id, "Transaksi Cash On Delivery berhasil diperbarui.", "Transaksi");
                $redirect = redirect()->route('customer.notification.index')->with('result', ['Success', 'Transaksi Cash On Delivery (Bayar Ditempat) berhasil diperbarui.']);
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
                    store_notif($customer->id, "Transaksi Anda tidak dapat diproses, karena gaji Anda tidak cukup", "Transaksi");
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
                store_notif($customer->id, "Data Kredit berhasil diupdate, mohon di cek untuk lebih detailnya", 'Transaksi');
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
                store_notif($customer->id, "Pembayaran melalui via transfer sedang menunggu untuk konfirmasi", 'Transaksi');
                $redirect = redirect()->route('customer.notification.transaction.index')->with('result', ['Success', 'Pembayaran melalui via transfer sedang menunggu untuk konfirmasi']);
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
        $overPrice = null;
        $currentDate = Carbon::now();
        if ($currentDate->gt($transaction->due_date) && $transaction->is_dp_paid == 1) {
            $overdate = $currentDate->diffInDays($transaction->due_date);
            $overPrice = $overdate  * ($transaction->total_payment * 0.02);
        }
        return view("customer.notifications.transaction.credit_payment", compact("transaction", "overPrice"));
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
            $notif = store_notif($transaction->customer_id, "Pembayaran Kredit Anda Terkirim", "Pengajuan Pembayaran Kredit");
            DB::commit();

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Bukti Transaksi Pembayaran Terkirim"]);
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
            $notif = store_notif($transaction->customer_id, " Pembayaran Uang Muka berhasil terkirim, mohon menunggu konfirmasi dari admin", "Pembayaran Uang Muka");
            DB::commit();

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Bukti Transaksi Pembayaran Terkirim"]);
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

            return redirect()->route('customer.notification.transaction.index')->with("result", ["success", "Bukti Transaksi Pembayaran Terkirim"]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->route("customer.notification.transaction.index")->with("result", ["error", "Failed"]);
        }
    }
}
