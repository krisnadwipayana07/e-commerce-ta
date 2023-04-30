<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory, UsesUuid;

    const STATUS_ORDER_RECEIVED = 'Order Received';
    const STATUS_IN_TRANSIT = 'In Transit';
    const STATUS_DELIVERED = 'Delivered';
    const STATUS_REJECTED = 'Rejected';

    protected $guarded = ['created_at', 'updated_at'];

    public static function make($customer_id, $transaction_id, $status = Delivery::STATUS_ORDER_RECEIVED)
    {
        Delivery::create([
            'customer_id' => $customer_id,
            'transaction_id' => $transaction_id,
            'status' => $status
        ]);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
