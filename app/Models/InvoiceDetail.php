<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class InvoiceDetail extends Model implements Auditable {
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'invoice_details';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'vat_amount',
    ];

    public function getTotalWithVatAttribute(): float {
        return $this->subtotal + $this->vat_amount;
    }

    public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class);
    }
}
