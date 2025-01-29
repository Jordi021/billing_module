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
        'product_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class);
    }
}
