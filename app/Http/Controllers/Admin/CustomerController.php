<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()){
            $data = Customer::orderBy('name', 'DESC')->get();;
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('status', function($data){
                        return returnStatus($data->status);
                    })
                    ->addColumn('action', function($data){
                        return actionBtn('Customer', route('admin.customer.index'), route('admin.customer.show', $data->id), route('admin.customer.edit', $data->id), route('admin.customer.destroy', $data->id));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.customer.index');
    }

    public function create(){
        return view('admin.customer.create');
    }

    public function store(Request $request) {
        $request->validate(
            [
                'username' => 'required|unique:customers,username',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'name' => 'required|unique:customers,name',
                'address' => 'required',
                'phone_number' => 'required|unique:customers,phone_number',
                'email' => 'required|unique:customers,email',
                'myimg' =>'required|mimes:jpeg,png|max:2048',
            ], [],
            [
                'username' => 'Username',
                'password' => 'Password',
                'password_confirmation' => 'Password Confirmation',
                'name' => 'Name',
                'address' => 'Address',
                'phone_number' => 'Phone Number',
                'email' => 'Email',
                'myimg' => 'Image',
            ]);
 
        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/admin/customer/');
                $request['img'] = $img_name;
            }
            $customer = Customer::create($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$customer->name.' Added Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function show(Customer $customer) {
        return view('admin.customer.show', ['data' => $customer]);
    }

    public function edit(Customer $customer) {
        return view('admin.customer.edit', ['data' => $customer]);
    }

    public function update(Request $request, Customer $customer) {
        $request->validate(
            [
                'username' => 'required|unique:customers,username,' . $customer->id,
                'name' => 'required|unique:customers,name,'. $customer->id,
                'address' => 'required',
                'phone_number' => 'required|unique:customers,phone_number,'. $customer->id,
                'email' => 'required|unique:customers,email,'. $customer->id,
                'myimg' =>'nullable|mimes:jpeg,png|max:2048',
            ], [],
            [
                'username' => 'Username',
                'name' => 'Name',
                'address' => 'Address',
                'phone_number' => 'Phone Number',
                'email' => 'Email',
                'myimg' => 'Image',
            ]);
            if($request->password || $request->password_confirmation){
                $request->validate(
                    [
                        'password' => 'required|confirmed',
                        'password_confirmation' => 'required',
                    ], [],
                    [
                        'password' => 'Password',
                        'password_confirmation' => 'Confirm Password',
                    ]);
            }

        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $request['img'] = updateImg('upload/admin/customer/', $customer->img);
            }
            
            if($request->password && $request->password_confirmation){
                $customer->update($request->all());
            }else{
                $customer->update($request->except(['password']));
            }
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Data #'.$customer->name.' Updated Successfully.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

    public function destroy(Request $request, Customer $customer) {
        DB::beginTransaction();
        try {
            
            // deleteImg('upload/admin/customer/', $customer->img);

            $customer->delete();
            DB::commit();
            $result = 'Data #'.$customer->name.' Deleted Successfully.';
            $request->session()->flash('result', ['success', $result]);
            return response()->json(['status' => 1, 'text' => $result]);
        }catch(Exception $ex){
            DB::rollBack();
            return response()->json(['status' => 0, 'text' => 'Error Occur.']);
        }
    }

}
