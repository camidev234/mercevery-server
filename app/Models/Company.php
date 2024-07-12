<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    public function payment_types(): BelongsToMany
    {
        return $this->belongsToMany(PaymentType::class, 'company_payment_type', 'company_id', 'payment_type_id')->withPivot('merchant_id', 'active');
    }

    public function company_sales() :HasMany {
        return $this->hasMany(CompanySale::class);
    }
}
