<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySale extends Model
{
    use HasFactory;

    public function sales_transaction() :BelongsTo {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function company() :BelongsTo {
        return $this->belongsTo(Company::class);
    }
}
