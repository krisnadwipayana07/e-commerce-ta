<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionDownPayment;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubmissionDownPaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubmissionDownPayment::with(['transaction', 'customer'])->orderBy('created_at', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($data) {
                    return $data->customer->name;
                })
                ->editColumn('status', function ($data) {
                    return ucwords($data->status);
                })
                ->addColumn('action', function ($data) {
                    return onlyShowBtn('Submission Down Payment', route('admin.submission.dp.payment.show', $data->id)) . onlyDeleteBtn('Submission Down Payment', route('admin.submission.dp.payment.delete', $data->id), route('admin.submission.dp.payment.index'));
                })
                ->rawColumns(['customer_name', 'action'])
                ->make(true);
        }
        return view('admin.submission_down_payment.index');
    }

    public function show(SubmissionDownPayment $submission_down_payment)
    {
        $submission_down_payment = SubmissionDownPayment::with(['transaction', 'customer'])->find($submission_down_payment->id);
        return view('admin.submission_down_payment.show', ["data" => $submission_down_payment]);
    }

    public function destroy(Request $request, SubmissionDownPayment $submission_down_payment)
    {
        DB::beginTransaction();
        try {
            $submission_down_payment->delete();
            DB::commit();
            $result = 'Data Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.', 'error' => strval($ex)]);
        }
    }

    public function approve(Request $request, SubmissionDownPayment $submission_down_payment)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::find($submission_down_payment->transaction_id);
            $updated = [
                "is_dp_paid" => true,
            ];

            $transaction->update($updated);
            $submission_down_payment->update([
                "status" => "accept"
            ]);

            DB::commit();

            return redirect()->route('admin.submission.dp.payment.index')->with("result", ["success", "Success approve payment"]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.submission.dp.payment.index')->with("result", ["error", "Failed approve payment"]);
        }
    }

    public function reject(Request $request, SubmissionDownPayment $submission_down_payment)
    {
        DB::beginTransaction();
        try {
            $submission_down_payment->update([
                "status" => "reject"
            ]);

            DB::commit();

            return redirect()->route('admin.submission.dp.payment')->with("result", ["success", "Success approve payment"]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.submission.dp.payment')->with("result", ["error", "Failed approve payment"]);
        }
    }
}
