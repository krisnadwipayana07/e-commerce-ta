<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CategoryPayment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoryPaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CategoryPayment::where('status', 'active')->orderBy('name', 'DESC')->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('status', function ($data) {
                        return returnStatus($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        return actionBtn('Category Payment', route('admin.category_payment.index'), route('admin.category_payment.show', $data->id), route('admin.category_payment.edit', $data->id), route('admin.category_payment.destroy', $data->id));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.category_payment.index');
    }

    public function create()
    {
        return view('admin.category_payment.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:category_payments,name',
            ],
            [],
            [
                'name' => 'Name',
            ]
        );

        DB::beginTransaction();
        try {
            //insert into category_payment values name=adaasdsa
            $categoryPayment = CategoryPayment::create($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$categoryPayment->name.' Added Successfully.']);
        } catch(Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function show(CategoryPayment $categoryPayment)
    {
        return view('admin.category_payment.show', ['data' => $categoryPayment]);
    }

    public function edit(CategoryPayment $categoryPayment)
    {
        return view('admin.category_payment.edit', ['data' => $categoryPayment]);
    }

    public function update(Request $request, CategoryPayment $categoryPayment)
    {
        $request->validate(
            [
                'name' => 'required|unique:category_payments,name,' . $categoryPayment->id,
                'status' => 'required',
            ],
            [],
            [
                'name' => 'Name',
            ]
        );

        DB::beginTransaction();
        try {
            $categoryPayment->update($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$categoryPayment->name.' Updated Successfully.']);
        } catch(Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function destroy(Request $request, CategoryPayment $categoryPayment)
    {
        DB::beginTransaction();
        try {
            $categoryPayment->delete();
            DB::commit();
            $result = 'Data #'.$categoryPayment->name.' Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }
}
