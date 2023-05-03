<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Property;
use App\Models\SubmissionTransferPayment;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubmissionTransferPaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubmissionTransferPayment::with(['transaction', 'customer'])->orderBy('created_at', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($data) {
                    return $data->customer->name;
                })
                ->addColumn('transaction_date', function ($data) {
                    return $data->transaction->created_at;
                })
                ->editColumn('status', function ($data) {
                    return ucwords($data->status);
                })
                ->addColumn('action', function ($data) {
                    return onlyShowBtn('Submission Transfer Payment', route('admin.submission.transfer.payment.show', $data->id)) . onlyDeleteBtn('Submission Transfer Payment', route('admin.submission.transfer.payment.delete', $data->id), route('admin.submission.transfer.payment.index'));
                })
                ->rawColumns(['customer_name', 'action'])
                ->make(true);
        }
        return view('admin.submission_transfer_payment.index');
    }

    public function show(SubmissionTransferPayment $submission_transfer_payment)
    {
        $submission_transfer_payment = SubmissionTransferPayment::with(['transaction', 'customer'])->find($submission_transfer_payment->id);
        return view('admin.submission_transfer_payment.show', ["data" => $submission_transfer_payment]);
    }

    public function destroy(Request $request, SubmissionTransferPayment $submission_transfer_payment)
    {
        DB::beginTransaction();
        try {
            $submission_transfer_payment->delete();
            DB::commit();
            $result = 'Data Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.', 'error' => strval($ex)]);
        }
    }

    public function approve(Request $request, SubmissionTransferPayment $submission_transfer_payment)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::find($submission_transfer_payment->transaction_id);
            $updated = [
                "status" => "paid"
            ];

            $transaction->update($updated);
            $submission_transfer_payment->update([
                "status" => "accept"
            ]);

            Delivery::make($submission_transfer_payment->customer_id, $submission_transfer_payment->transaction_id, Delivery::STATUS_IN_TRANSIT);

            DB::commit();

            return redirect()->route('admin.submission.transfer.payment.index')->with("result", ["success", "Success approve payment"]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.submission.transfer.payment.index')->with("result", ["error", "Failed approve payment"]);
        }
    }

    public function reject(Request $request, SubmissionTransferPayment $submission_transfer_payment)
    {
        DB::beginTransaction();
        try {
            $submission_transfer_payment->update([
                "status" => "reject"
            ]);

            restore_property_stocks($submission_transfer_payment->transaction_id);
            Delivery::make($submission_transfer_payment->customer_id, $submission_transfer_payment->transaction_id, Delivery::STATUS_REJECTED);

            DB::commit();

            return redirect()->route('admin.submission.transfer.payment.index')->with("result", ["success", "Success approve payment"]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.submission.transfer.payment.index')->with("result", ["error", "Failed approve payment"]);
        }
    }
}
