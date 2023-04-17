<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionPremiumCustomer extends Model
{
    use \App\Http\Traits\UsesUuid;
    use HasFactory;
    protected $guarded = ['created_at', 'updated_at'];

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
}
