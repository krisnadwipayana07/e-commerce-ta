<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Treasure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use AuthenticatesUsers;
    public function form_admin() {
        return view('auth.register.admin');
    }

    public function register_admin(Request $request) {
        $request->validate(
            [
                'username' => 'required|unique:admins,username',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'name' => 'required|unique:admins,name',
                'address' => 'required',
                'phone_number' => 'required|unique:admins,phone_number',
                'myimg' =>'required|mimes:jpeg,png|max:2048',
            ], [],
            [
                'username' => 'Username',
                'password' => 'Password',
                'password_confirmation' => 'Password Confirmation',
                'name' => 'Name',
                'address' => 'Address',
                'phone_number' => 'Phone Number',
                'myimg' => 'Image',
            ]);
 
        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/admin/admin/');
                $request['img'] = $img_name;
            }
            $admin = Admin::create($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Berhasil-'.$admin->name.'Melakukan Pendaftaran']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function form_staff() {
        return view('auth.register.staff');
    }

    public function register_staff(Request $request) {
        $request->validate(
            [
                'id_number' => 'required|unique:staff,id_number',
                'username' => 'required|unique:staff,username',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'name' => 'required|unique:staff,name',
                'address' => 'required',
                'phone_number' => 'required|unique:staff,phone_number',
                'myimg' =>'required|mimes:jpeg,png|max:2048',
            ], [],
            [
                'id_number' => 'ID Number',
                'username' => 'Username',
                'password' => 'Password',
                'password_confirmation' => 'Password Confirmation',
                'name' => 'Name',
                'address' => 'Address',
                'phone_number' => 'Phone Number',
                'myimg' => 'Image',
            ]);
 
        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/admin/staff/');
                $request['img'] = $img_name;
            }
            $request['status'] = 'pending';
            $staff = Staff::create($request->all());
            DB::commit();
            return redirect()->route('auth.login.form_staff')->with('result', ['success', 'Data #'.$staff->name.' added successfully, You can login after admin approve.']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function form_treasure() {
        return view('auth.register.treasure');
    }

    public function register_treasure(Request $request) {
        $request->validate(
            [
                'id_number' => 'required|unique:treasures,id_number',
                'username' => 'required|unique:treasures,username',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'name' => 'required|unique:treasures,name',
                'address' => 'required',
                'phone_number' => 'required|unique:treasures,phone_number',
                'myimg' =>'required|mimes:jpeg,png|max:2048',
            ], [],
            [
                'id_number' => 'ID Number',
                'username' => 'Username',
                'password' => 'Password',
                'password_confirmation' => 'Password Confirmation',
                'name' => 'Name',
                'address' => 'Address',
                'phone_number' => 'Phone Number',
                'myimg' => 'Image',
            ]);
 
        DB::beginTransaction();
        try{
            if ($request->hasFile('myimg')) {
                $img_name = insertImg('upload/admin/treasure/');
                $request['img'] = $img_name;
            }
            $treasure = Treasure::create($request->all());
            DB::commit();
            return redirect()->back()->with('result', ['success', 'Berhasil-'.$treasure->name.'Melakukan Pendaftaran']);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json(['status' => 0, 'text' => $ex->getMessage()]);
        }
    }

    public function form_customer() {
        return view('auth.register.customer');
    }

    public function register_customer(Request $request) {
        $request->validate(
            [
                'username' => 'required|unique:customers,username',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'name' => 'required|unique:customers,name',
                'address' => 'required',
                'phone_number' => 'required|unique:customers,phone_number',
                'email' => 'required|unique:customers,email',
                'gender' => 'required',
                'birth_date' => 'required',
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
                'gender' => 'Gender',
                'birth_date' => 'Birth Date',
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
            return redirect()->route('landing.index')->with('result', ['success', 'Berhasil-'.$customer->name.' Melakukan Pendaftaran.']);
            
        }catch(Exception $ex){
            DB::rollback();
            dd($ex);
            return redirect()->back()->with('result', ['error', 'Data #'.$customer->name.' Added Failed.']);
        }
    }
}
