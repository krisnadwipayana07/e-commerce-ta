<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionCreditPayment;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubmissionCreditPaymentController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = SubmissionCreditPayment::with(['transaction', 'customer'])->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('customer_name', function($data) {
                        return $data->customer->name;
                    })
                    ->editColumn('status', function($data) {
                        return ucwords($data->status);
                    })
                    ->addColumn('action', function($data){
                        return onlyShowBtn('Submission Credit Payment', route('admin.submission.credit.payment.show', $data->id)) . onlyDeleteBtn('Submission Credit Payment', route('admin.submission.credit.payment.delete', $data->id), route('admin.submission.credit.payment.index'));
                    })
                    ->rawColumns(['customer_name', 'action'])
                    ->make(true);
        }
        return view('admin.submission_credit_payment.index');
    }

    public function show(SubmissionCreditPayment $submission_credit_payment)
    {
        $submission_credit_payment = SubmissionCreditPayment::with(['transaction', 'customer'])->find($submission_credit_payment->id);
        return view('admin.submission_credit_payment.show', ["data" => $submission_credit_payment]);
    }
    
    public function destroy(Request $request, SubmissionCreditPayment $submission_credit_payment)
    {
        DB::beginTransaction();
        try {
            $submission_credit_payment->delete();
            DB::commit();
            $result = 'Data Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        }catch(Exception $ex){
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.', 'error' => strval($ex)]);
        }
    }

    public function approve(Request $request, SubmissionCreditPayment $submission_credit_payment)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::find($submission_credit_payment->transaction_id);
            $updated = [
                "total_phase" => $submission_credit_payment->payment_phase
            ];

            if ($submission_credit_payment->payment_phase == $transaction->credit_period) {
                $updated["status"] = 'paid';
            }

            $transaction->update($updated);
            $submission_credit_payment->update([
                "status" => "accept"
            ]);

            DB::commit();

            return redirect()->route('admin.submission.credit.payment.index')->with("result", ["success", "Success approve payment"]);
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.submission.credit.payment.index')->with("result", ["error", "Failed approve payment"]);
        }
    }
    
    public function reject(Request $request, SubmissionCreditPayment $submission_credit_payment)
    {
        DB::beginTransaction();
        try {
            $submission_credit_payment->update([
                "status" => "reject"
            ]);
            
            DB::commit();

            return redirect()->route('admin.submission.credit.payment')->with("result", ["success", "Success approve payment"]);
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.submission.credit.payment')->with("result", ["error", "Failed approve payment"]);
        }
    }
}
