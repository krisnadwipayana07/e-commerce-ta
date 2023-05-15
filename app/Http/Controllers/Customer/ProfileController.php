<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CategoryProperty;
use App\Models\Customer;
use App\Models\SubmissionPremiumCustomer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    protected $category_product;
    protected $gender = [
        "Male" => "Laki - laki",
        "Female" => "Wanita"
    ];
    public function __construct()
    {
        $this->category_product = CategoryProperty::where('status', 'active')->get();
    }

    public function index()
    {
        $user = Customer::where('id', auth()->guard('customer')->user()->id)->first();
        $user->gender = $this->gender[$user->gender];
        $submission_premium = SubmissionPremiumCustomer::where("customer_id", $user->id)->get()->count();
        $isPremium = $submission_premium > 0 ? true : false;
        $header_category = $this->category_product;
        return view('landing.profile', compact('user', 'header_category', 'isPremium'));
    }

    public function edit(Customer $customer)
    {
        $header_category = $this->category_product;
        return view('landing.change_profile', compact('customer', 'header_category'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate(
            [
                'name' => 'required|unique:customers,name,' . $customer->id,
                'address' => 'required',
                'phone_number' => 'required|unique:customers,phone_number,' . $customer->id,
                'email' => 'required|unique:customers,email,' . $customer->id,
                'myimg' => 'nullable|mimes:jpeg,png|max:2048',
                'password' => 'required'
            ],
            [
                'password.required' => 'Mohon menginputkan kata sandi agar dapat melanjutkan'
            ],
            [
                'name' => 'Name',
                'address' => 'Address',
                'phone_number' => 'Phone Number',
                'email' => 'Email',
                'myimg' => 'Image',
                'password' => 'Password',
            ]
        );
        // if ($request->password || $request->password_confirmation) {
        //     $request->validate(
        //         [
        //             'password' => 'required|confirmed',
        //             'password_confirmation' => 'required',
        //         ],
        //         [],
        //         [
        //             'password' => 'Password',
        //             'password_confirmation' => 'Confirm Password',
        //         ]
        //     );
        // }

        DB::beginTransaction();
        try {
            if (!Hash::check($request->password, $customer->password)) {
                return redirect()->back()->with('result', ['error', 'kata sandi anda salah!']);
            }
            if ($request->hasFile('myimg')) {
                $request['img'] = updateImg('upload/admin/customer/', $customer->img);
            }

            if ($request->password_confirmation) {
                $request['password'] = $request->password_confirmation;
                $customer->update($request->all());
            } else {
                $customer->update($request->except(['password']));
            }
            // if ($request->password && $request->password_confirmation) {
            //     $customer->update($request->all());
            // } else {
            //     $customer->update($request->except(['password']));
            // }
            DB::commit();
            return redirect()->back()->with('result', ['success', ' Telah ' . 'Berhasil Diubah.']);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function submission_premium_index()
    {
        $user = Customer::where('id', auth()->guard('customer')->user()->id)->first();
        $header_category = $this->category_product;
        return view('landing.submission_premium', compact('user', 'header_category'));
    }

    public function submission_premium_store(Request $request)
    {
        DB::beginTransaction();
        try {
            $upload_path = "/upload/customer/submission_premium/";
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
            SubmissionPremiumCustomer::create([
                "customer_id" => auth()->guard('customer')->user()->id,
                "ktp_name" => $request->ktp_name,
                "ktp_number" => $request->ktp_number,
                "ktp_address" => $request->ktp_address,
                "ktp" => $ktp_name,
                "salary_slip" => $salary_slip_name,
                "photo" => $photo_name,
                "salary" => $request->salary
            ]);

            $notif = store_notif(auth()->guard('customer')->user()->id, "Pengajuan Anda ke akun premium telah dikirim", "Submission Premium Customer");
            DB::commit();
            return redirect()->route('customer.profile.index')->with('result', ['Success', "Penyerahan Akun Premium Terkirim"]);
        } catch (Exception $err) {
            DB::rollBack();
            Log::debug($err);
            return redirect()->back()->with('result', ['error', "Pengajuan Akun Premium Gagal"]);
        }
    }

    public function change_password_index(Customer $customer)
    {
        $header_category = $this->category_product;
        return view('landing.change_password', compact('customer', 'header_category'));
    }

    public function change_password_update(Request $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $correct_password = Hash::check($request->old_password, $customer->password);
            if (!$correct_password) {
                Log::debug("Not correct");
                return redirect()->back()->with('result', ['error', 'Kata Sandi Lama Tidak Cocok']);
            }

            if ($request->password != $request->confirmation_new_password) {
                Log::debug("Not match");
                return redirect()->back()->with('result', ['error', 'Kata sandi konfirmasi tidak cocok']);
            }

            $customer->update($request->only(['password']));

            DB::commit();
            return redirect()->route('customer.profile.index')->with('result', ['success', 'Kata sandi diubah']);
        } catch (Exception $err) {
            DB::rollBack();
            Log::debug("========= Ganti Password =========");
            Log::debug($err);
            return redirect()->back()->with('result', ['error', 'Somethings error']);
        }
    }
}
