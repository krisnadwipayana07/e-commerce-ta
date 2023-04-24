<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SubmissionPremiumCustomer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubmissionPremiumController extends Controller
{
    public function submission_premium_index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubmissionPremiumCustomer::join('customers', 'submission_premium_customers.customer_id', '=', 'customers.id')->orderBy('submission_premium_customers.created_at', 'ASC')->get(['submission_premium_customers.id as id', 'customers.name as name', 'customers.allow_credit as credit']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('credit', function ($data) {
                    $credit = $data->credit ? "Active" : "Not Active";
                    return $credit;
                })
                ->addColumn('action', function ($data) {
                    return onlyShowBtn('Submission Premium Customer', route('admin.submission_premium.show', $data->id));
                })
                ->rawColumns(['credit', 'action'])
                ->make(true);
        }
        return view('admin.submission_premium.index');
    }

    public function submission_premium_show(SubmissionPremiumCustomer $submission_premium_customer)
    {
        $customer = Customer::where('id', $submission_premium_customer->customer_id)->first();
        return view('admin.submission_premium.show', ['data' => $submission_premium_customer, 'customer' => $customer]);
    }

    public function submission_premium_approve(Request $request, SubmissionPremiumCustomer $submission_premium_customer)
    {
        $request->validate([
            "status" => "required"
        ], [], [
            'status' => 'Status',
        ]);
        DB::beginTransaction();
        try {
            $customer = Customer::where('id', $submission_premium_customer->customer_id);
            $customer->update([
                'allow_credit' => true,
                'salary' => $submission_premium_customer->salary
            ]);
            $notif = store_notif($submission_premium_customer->customer_id, "Akun berhasil di upgrade ke premium", "Submission Premium Customer");
            DB::commit();
            return redirect()->route('admin.submission_premium.index');
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function submission_premium_reject(SubmissionPremiumCustomer $submission_premium_customer)
    {
        DB::beginTransaction();
        try {
            $submission_premium_customer->delete();
            $notif = store_notif($submission_premium_customer->customer_id, "Maaf akun anda belum dapat menjadi premium, mohon check kembali data yang anda inputkan", "Submission Premium Customer");
            DB::commit();
            return redirect()->route('admin.submission_premium.index');
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }
}
