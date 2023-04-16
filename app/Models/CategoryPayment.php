<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryPayment extends Model
{
    use \App\Http\Traits\UsesUuid;
    use HasFactory;
    use SoftDeletes;

    // CategoryPayment
    // category_payments

    // protected $table = 'category_payment';

    protected $dates = ['deleted_at'];
    protected $guarded = ['created_at', 'updated_at'];
}
