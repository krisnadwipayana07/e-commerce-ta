<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function form_admin()
    {
        if (auth()->guard('admin')->user()) {
            return redirect()->route('admin.admin.index');
        }
        return view('auth.login.admin');
    }

    public function login_admin(Request $request)
    {
        if (auth()->guard('admin')->attempt($request->only('username', 'password'))) {
            return redirect()->route('admin.dashboard.index');
        } else {
            //inputan yang dikirm ke halaman sebelumnya
            return redirect()->back()->withInput()->with('result', ['error', 'Login failed, please try again!']);
        }
    }

    public function form_customer()
    {
        if (auth()->guard('customer')->user()) {
            return redirect()->route('customer.customer.index');
        }
        return view('auth.login.customer');
    }

    public function login_customer(Request $request)
    {
        if (auth()->guard('customer')->attempt($request->only('username', 'password'))) {
            return redirect()->route('landing.index');
        } else {
            return redirect()->back()->withInput()->with('result', ['error', 'Login failed, please try again!']);
        }
    }

    public function form_treasure()
    {
        if (auth()->guard('treasure')->user()) {
            return redirect()->route('treasure.treasure.index');
        }
        return view('auth.login.treasure');
    }

    public function login_treasure(Request $request)
    {
        if (auth()->guard('treasure')->attempt($request->only('username', 'password'))) {
            return redirect()->route('treasure.treasure.index');
        } else {
            return redirect()->back()->withInput()->with('result', ['error', 'Login failed, please try again!']);
        }
    }
}
