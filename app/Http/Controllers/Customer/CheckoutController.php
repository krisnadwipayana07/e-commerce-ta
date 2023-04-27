<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Property;
use App\Models\SubmissionCreditTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $category_product;
    public function __construct()
    {
        $this->category_product = CategoryProperty::where('status', 'active')->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('customer')->user();
        $carts = Cart::where('user_id', $user->id)->get();
        $payments = CategoryPayment::all();
        $header_category = $this->category_product;
        return view('customer.checkout.index', compact('carts', 'header_category', 'payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
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
                'category_payment_id' => 'Payment Type',
                'recipient_name' => 'Recipient Name',
                'deliver_to' => 'Deliver To',
                'account_number' => 'Account Number',
                'evidence_payment' => 'Approved Payment Customer',
                'credit_period' => 'Credit Periode',
                'total' => 'Total'
            ]
        );
        DB::beginTransaction();
        try {
            $customer = Auth::guard('customer')->user();
            $transaction = Transaction::where('customer_id', $customer->id)->withTrashed()->get()->count();
            $code = "TRX-" . strval($transaction + 1) . "-CS-" . $customer->id;
            $payment = CategoryPayment::where('id', $request->category_payment_id)->first();
            $admin = Admin::all();
            if ($payment->name == "Cash" || $payment->name == "Cash On Delivery") {
                $trx = Transaction::create([
                    'code' => $code,
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    'total_payment' => $request->total,
                    'customer_id' => $customer->id,
                    'admin_id' => $admin[0]->id,
                    'status' => "pending",
                    "latitude" => $request->lat,
                    "longitude" => $request->lng
                ]);
                $notif = store_notif($customer->id, "Pembelian barang sukses. Lakukan pembayaran saat barang sudah sampai", "Transaction");
                $redirect = redirect()->route('landing.index')->with('result', ['success', 'Success melakukan pembelian barang']);
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
                    $notif = store_notif($customer->id, "Transaksi Anda tidak dapat diproses, karena gaji Anda tidak cukup", "Transaction");
                    return redirect()->back()->with("result", ["error", "Transaksi Anda tidak dapat diproses, karena gaji Anda tidak cukup"]);
                }

                $trx = Transaction::create([
                    'code' => $code,
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    'total_payment' => $request->total,
                    'customer_id' => $customer->id,
                    'admin_id' => $admin[0]->id,
                    'credit_period' => $request->credit_period,
                    'payment_credit' => $request->payment_credit,
                    'down_payment' => $request->down_payment,
                    'status' => "pending",
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

                SubmissionCreditTransaction::create([
                    "transaction_id" => $trx->id,
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

                $notif = store_notif(Auth::guard('customer')->user()->id, "Data kredit telah diterima. dengan detail berikut : Periode Kredit : " . strval($request->credit_period) . "x Dan DP Sebesar : " . strval(IDRConvert($request->down_payment)), 'Transaction');

                $redirect = redirect()->route('customer.notification.index')->with('result', ['Berhasil', 'Berhasil mengirimkan pengajuan kredit']);
            } else {
                // if (!$request->hasFile('myimg')) {
                //     return redirect()->back()->with('result', ['error', 'File payment must be upload first in this payment type.']);
                // }
                // $img_name = insertImg('/upload/evidence_payment/');
                $due_date = Carbon::now()->addDay();
                $trx = Transaction::create([
                    'code' => $code,
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    // 'img' => $img_name,
                    'total_payment' => $request->total,
                    'customer_id' => $customer->id,
                    'admin_id' => $admin[0]->id,
                    'status' => "in_progress",
                    'due_date' => $due_date,
                    "latitude" => $request->lat,
                    "longitude" => $request->lng
                ]);

                $notif = store_notif(Auth::guard('customer')->user()->id, "Pembayaran melalui transfer bank menunggu konfirmasi", 'Transaction');

                $redirect = redirect()->route('customer.notification.transaction.index')->with('result', ['Berhasil', 'Silakan Lakukan pembayaran']);
            }
            $carts = Cart::where('user_id', $customer->id)->get();
            foreach ($carts as $cart) {
                $property = Property::where('id', $cart->property->id)->first();
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'property_id' => $cart->property_id,
                    'qty' => $cart->quantity,
                    'price' => $property->price,
                    'total_price' => $property->price * $cart->quantity
                ]);
                $property->update([
                    'stock' => $property->stock - $cart->quantity
                ]);
                $cart->delete();
            }
            DB::commit();

            return $redirect;
        } catch (Exception $ex) {
            Log::debug($ex);
            DB::rollback();
            return redirect()->back()->with('result', ['error', 'Somethings Error: ' . $ex]);
        }
    }

    public function show()
    {
        return response()->json([
            "message" => "show"
        ]);
    }

    public function single_index(Request $request)
    {
        $user = Auth::guard('customer')->user();
        $property = Property::where('id', $request->property_id)->first();
        $quantity = $request->quantity;
        $payments = CategoryPayment::all();
        $header_category = $this->category_product;
        return view('customer.checkout.single_index', compact('property', 'header_category', 'payments', 'quantity'));
    }

    public function single_store(Request $request)
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
            $customer = Auth::guard('customer')->user();
            $transaction = Transaction::where('customer_id', $customer->id)->withTrashed()->get()->count();
            $code = "TRX-" . strval($transaction + 1) . "-CS-" . $customer->id;
            $payment = CategoryPayment::where('id', $request->category_payment_id)->first();
            $admin = Admin::all();
            if ($payment->name == "Cash" || $payment->name == "Cash On Delivery") {
                $trx = Transaction::create([
                    'code' => $code,
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    'total_payment' => $request->total,
                    'customer_id' => $customer->id,
                    'admin_id' => $admin[0]->id,
                    'status' => "pending",
                    "latitude" => $request->lat,
                    "longitude" => $request->lng
                ]);
                $notif = store_notif($customer->id, "Pembelian tunai yang sukses. Lakukan pembayaran saat barang sudah sampai", "Transaction");
                $redirect = redirect()->route('landing.index')->with('result', ['Berhasil', 'Lakukan pembayaran saat barang sudah sampai']);
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
                    $notif = store_notif($customer->id, "Transaksi Anda tidak dapat diproses, karena gaji Anda tidak cukup", "Transaction");
                    return redirect()->back()->with("result", ["error", "Transaksi Anda tidak dapat diproses, karena gaji Anda tidak cukup"]);
                }

                $trx = Transaction::create([
                    'code' => $code,
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    'total_payment' => $request->total,
                    'customer_id' => $customer->id,
                    'admin_id' => $admin[0]->id,
                    'credit_period' => $request->credit_period,
                    'payment_credit' => $request->payment_credit,
                    'down_payment' => $request->down_payment,
                    'status' => "pending",
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

                SubmissionCreditTransaction::create([
                    "transaction_id" => $trx->id,
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
                    "education" => $request->education,
                    "marital_status" => $request->marital_status,
                    "jobs" => $request->jobs,
                    "company_name" => $request->company_name,
                    "company_address" => $request->company_address,
                    "company_phone" => $request->company_phone,
                    "length_of_work" => $request->length_of_work,
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

                $notif = store_notif(Auth::guard('customer')->user()->id, "Data kredit telah diterima. dengan detail berikut : Periode Kredit : " . strval($request->credit_period) . "x Dan DP Sebesar : " . strval(IDRConvert($request->down_payment)), 'Transaction');

                $redirect = redirect()->route('customer.notification.index')->with('result', ['Berhasil', 'Berhasil mengirimkan pengajuan kredit']);
            } else {
                // if (!$request->hasFile('myimg')) {
                //     return redirect()->back()->with('result', ['error', 'File payment must be upload first in this payment type.']);
                // }
                // $img_name = insertImg('/upload/evidence_payment/');
                $due_date = Carbon::now()->addDay();
                $trx = Transaction::create([
                    'code' => $code,
                    'category_payment_id' => $request->category_payment_id,
                    'recipient_name' => $request->recipient_name,
                    'deliver_to' => $request->deliver_to,
                    'account_number' => $request->account_number,
                    // 'img' => $img_name,
                    'total_payment' => $request->total,
                    'customer_id' => $customer->id,
                    'admin_id' => $admin[0]->id,
                    'status' => "in_progress",
                    'due_date' => $due_date,
                    "latitude" => $request->lat,
                    "longitude" => $request->lng
                ]);

                $notif = store_notif(Auth::guard('customer')->user()->id, "Pembayaran melalui transfer bank menunggu konfirmasi", 'Transaction');

                $redirect = redirect()->route('customer.notification.transaction.index')->with('result', ['Berhasil', 'Silakan Lakukan pembayaran']);
            }
            $property = Property::where('id', $request->property_id)->first();
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'property_id' => $request->property_id,
                'qty' => $request->quantity_property,
                'price' => $property->price,
                'total_price' => $property->price * $request->quantity_property
            ]);
            $property->update([
                'stock' => $property->stock - $request->quantity_property
            ]);
            DB::commit();

            return $redirect;
        } catch (Exception $ex) {
            Log::debug($ex);
            DB::rollback();
            return redirect()->route('landing.index')->with('result', ['error', 'Somethings Error: ' . $ex]);
        }
    }

    public function edit_quantity(Cart $cart)
    {
        $header_category = $this->category_product;
        return view('customer.checkout.edit', compact('cart', 'header_category'));
    }

    public function update_quantity(Request $request, Cart $cart)
    {
        $request->validate(
            [
                'quantity' => 'required'
            ],
            [],
            [
                'quantity' => 'Quantity'
            ]
        );
        DB::beginTransaction();
        try {
            $cart->update([
                'quantity' => $request->quantity
            ]);
            DB::commit();

            return redirect()->route('customer.checkout.index');
        } catch (Exception $ex) {
            Log::debug($ex);
            DB::rollback();
            return redirect()->back()->with("result", ["error", "Something error " . $ex]);
        }
    }

    public function single_edit_quantity(Request $request)
    {
        $property = Property::where('id', $request->property_id)->first();
        $quantity = $request->quantity;
        $header_category = $this->category_product;
        return view('customer.checkout.single_edit', compact('property', 'header_category', 'quantity'));
    }
}
