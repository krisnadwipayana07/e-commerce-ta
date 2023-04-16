<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransferPaymentController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Transaction::where('due_date', '>', Carbon::now())->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('status', function($data) {
                        return ucwords($data->status);
                    })
                    ->addColumn('action', function($data){
                        return onlyDeleteBtn('Transfer Payment', route('admin.transfer.payment.delete', $data->id), route('admin.transfer.payment.index'));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.transfer_payment.index');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            $transaction_detail = TransactionDetail::where('transaction_id', $transaction->id)->get();
            foreach ($transaction_detail as $item) {
                $property = Property::where('id', $item->property_id)->first();
                
                $property->update([
                    'stock' => $$property->stock + $item->qty
                ]);
            }
            $transaction->delete();
            DB::commit();
            $result = 'Data Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        }catch(Exception $ex){
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.', 'error' => strval($ex)]);
        }
    }
}
