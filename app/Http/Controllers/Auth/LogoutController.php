<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LogoutController extends Controller
{
    use AuthenticatesUsers;
    // public function logout()
    // {
    //     auth()->guard('admin')->logout();
    //     auth()->guard('customer')->logout();
    //     return redirect()->route('landing.index');
    // }

    public function logout_admin()
    {
        auth()->guard('admin')->logout();
        auth()->guard('customer')->logout();
        return redirect()->route('auth.login.form_admin');
    }
    
    public function logout_customer()
    {
        auth()->guard('admin')->logout();
        auth()->guard('customer')->logout();
        return redirect()->route('landing.index');
    }
}
