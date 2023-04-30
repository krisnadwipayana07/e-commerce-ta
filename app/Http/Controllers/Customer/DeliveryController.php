<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::where('customer_id', Auth::guard('customer')->user()->id)
            ->orderBy('transaction_id')
            ->orderBy('created_at', 'desc')
            ->get()->groupBy('transaction_id');
        dd($deliveries);
        // return view('view-name', compact($deliveries));
    }

    public function show(Delivery $delivery)
    {
        dd($delivery);
        // return view('view-name', compact($delivery));
    }

    public function accept_delivery($deliveryId)
    {
        try {
            DB::beginTransaction();
            $delivery = Delivery::findOrNull($deliveryId);
            
            if ($delivery != null) {
                $delivery->update([
                    'is_received' => true
                ]);
            } else {
                return redirect()->back()->with('result', ['error' => 'Data not found']);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::debug($e);
            return redirect()->back()->with('result', ['error' => 'Failed to accept the delivery']);
        }
    }
}
