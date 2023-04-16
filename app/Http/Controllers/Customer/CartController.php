<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\CategoryProperty;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $category_product;
    public function __construct()
    {
        $this->category_product = CategoryProperty::where('status', 'active')->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('customer')->user();
        $carts = Cart::where('user_id', $user->id)->get();
        $header_category = $this->category_product;
        return view('customer.cart.index', compact('carts', 'header_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'property_id' => 'required|exists:properties,id',
                'quantity' => 'required'
            ], [],
            [
                'property_id' => 'Property',
                'quantity' => 'Quantity'
            ]
        );
        DB::beginTransaction();
        try {
            Cart::create([
                'property_id' => $request->property_id,
                'user_id' => Auth::guard('customer')->user()->id,
                'quantity' => $request->quantity
            ]);
            DB::commit();
            return redirect()->route('landing.index')->with('result', ['success', 'Success add property to cart']);
        }catch(Exception $ex){
            Log::debug($ex);
            DB::rollback();
            return redirect()->back()->with('result', ['error', 'Add property to cart failed']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        $header_category = $this->category_product;
        return view('customer.cart.edit', compact('cart', 'header_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate(
            [
                'quantity' => 'required'
            ], [],
            [
                'quantity' => 'Quantity'
            ]
        );
        DB::beginTransaction();
        try {
            $cart->update([
                'quantity' => $request->quantity
            ]);
            DB::commit();

            return redirect()->route('customer.cart.index');
        }catch(Exception $ex){
            Log::debug($ex);
            DB::rollback();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        $user = Auth::guard('customer')->user();
        $carts = Cart::where('user_id', $user->id);
        if ($carts->count() > 0) {
            return redirect()->back();
        } else {
            return redirect()->route('landing.index');
        }
    }
}
