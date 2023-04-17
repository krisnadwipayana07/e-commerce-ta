<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Customer;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()){
            $data = Transaction::where('customer_id', auth()->guard('customer')->user()->id)
            ->with(['category_payment'])
            ->orderBy('created_at', 'ASC')
            ->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('category_payment_id', function($data){
                        return $data->category_payment->name;
                    })
                    ->editColumn('status', function($data){
                        return returnStatusOrder($data->status);
                    })
                    ->addColumn('action', function($data){
                        return onlyShowBtn('Transaction', route('customer.transaction.show', $data->id));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        // $category_payment = CategoryPayment::where('status','active')->orderBy('name', 'ASC')->get();
        // $category_property = CategoryProperty::where('status','active')
        //     ->with([
        //         'property' => function($q){
        //             return $q->where('status','active')->get();
        //         }
        //     ])
        //     ->orderBy('name', 'ASC')
        //     ->get();
        return view('customer.transaction.index');
    }

    public function create(){
        $category_payment = CategoryPayment::where('status','active')->orderBy('name', 'ASC')->get();
        $category_property = CategoryProperty::where('status','active')
            ->with([
                'sub_category_property' => function($q){
                    return $q->where('status','active')
                            ->with([
                                'property' => function($q2){
                                    return $q2->where('status','active')->get();
                                }
                            ])->get();
                }
            ])
            ->orderBy('name', 'ASC')
            ->get();
        return view('customer.transaction.create', compact('category_property', 'category_payment'));
    }

    public function store(Request $request) {
        $request->validate(
            [
                'category_payment_id' => 'required',
                'recipient_name' => 'required',
                'deliver_to' => 'required',
                'total_payment' => 'required',
                'choose_property_id.*' => 'required',
                'choose_property_qty.*' => 'required',
                'choose_property_price.*' => 'required',
            ], [],
            [
                'category_payment_id' => 'Category Payment',
                'recipient_name' => 'Recipient Name',
                'deliver_to' => 'Deliver To',
                'total_payment' => 'Total Payment',
                'choose_property_id.*' => 'Property',
                'choose_property_qty.*' => 'Qty',
                'choose_property_price.*' => 'Price',
            ]);
 
        DB::beginTransaction();
        try{
            $request['customer_id'] = auth()->guard('customer')->user()->id;
            $transaction = Transaction::create($request->all());
            do{
                $code = generateOrderNumber($transaction->id);
                $check_transaction = Transaction::where('code', $code)->first();
            }while($check_transaction);
            $transaction->update(['code' => $code]);

            // Transaction Detail
            foreach($request->choose_property_id as $key => $item){
                if($request->choose_property_qty[$key] > 0){
                    $transactionDetail = TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'property_id' => $item,
                        'qty' => $request->choose_property_qty[$key],
                        'price' => $request->choose_property_price[$key],
                        'total_price' => (int)$request->choose_property_price[$key] * (int)$request->choose_property_qty[$key],
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$transaction->code.' Added Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function show(Transaction $transaction) {
        return view('customer.transaction.show', ['data' => $transaction]);
    }

    public function edit(Transaction $transaction) {
        $category_payment = CategoryPayment::where('status','active')->orderBy('name', 'ASC')->get();
        $category_property = CategoryProperty::where('status','active')
            ->with([
                'sub_category_property' => function($q){
                    return $q->where('status','active')
                            ->with([
                                'property' => function($q2){
                                    return $q2->where('status','active')->get();
                                }
                            ])->get();
                }
            ])
            ->orderBy('name', 'ASC')
            ->get();
        return view('customer.transaction.edit', ['data' => $transaction, 'category_payment' => $category_payment, 'category_property' => $category_property]);
    }

    public function update(Request $request, Transaction $transaction) {
        $request->validate(
            [
                'myimg' =>'required|mimes:jpeg,png|max:2048',
            ], [],
            [
                'myimg' => 'Image',
            ]);

        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $request['img'] = updateImg('upload/customer/transaction/', $transaction->img);
            }
            
            $transaction->update(['img' => $request['img']]);
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$transaction->code.' Updated Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function destroy(Request $request, Transaction $transaction) {
        DB::beginTransaction();
        try {
            
            // deleteImg('upload/customer/transaction/', $transaction->img);

            $transaction->delete();
            DB::commit();
            $result = 'Data #'.$transaction->name.' Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        }catch(Exception $ex){
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

}
