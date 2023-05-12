<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionDeliveryPayment extends Model
{
    use HasFactory, UsesUuid;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
