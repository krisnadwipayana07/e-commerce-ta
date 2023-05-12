<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionDeliveryPayment extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'transaction_id',
        'product_evidence',
        'signature_evidence'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
