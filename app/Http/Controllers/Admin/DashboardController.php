<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryPayment;
use App\Models\CategoryProperty;
use App\Models\Customer;
use App\Models\Property;
use App\Models\SubCategoryProperty;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //select * from category_payment where status = active
        $totalCategoryPayment = CategoryPayment::where('status', 'active')->count();
        // dd($totalCategoryPayment);
        $totalCategory = CategoryProperty::where('status', 'active')->count();
        $totalSubCategory = SubCategoryProperty::where('status', 'active')->count();
        $totalProperty = Property::where('status', 'active')->count();
        $totalCustomer = Customer::count();
        $totalTransaction = Transaction::where('status', '=', 'paid')->count();
        return view('admin.dashboard.index', compact('totalCategory', 'totalSubCategory', 'totalCustomer', 'totalProperty', 'totalTransaction', 'totalCategoryPayment'));
    }
}
