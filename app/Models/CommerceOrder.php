<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommerceOrder extends Model
{
    use HasFactory;

    public function order_status() :BelongsTo {
        return $this->belongsTo(OrderStatus::class);
    }

    public function company() :BelongsTo {
        return $this->belongsTo(Company::class);
    }

    public function payment_type() :BelongsTo {
        return $this->belongsTo(PaymentType::class);
    }

    public function user() :BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function order_products() :HasMany {
        return $this->hasMany(OrderProduct::class);
    }

    public function sales_transactions() :HasMany {
        return $this->hasMany(SalesTransaction::class);
    }
}
