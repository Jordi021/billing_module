<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model implements Auditable {
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'payment_type',
        'invoice_date',
        'total',
        'note',
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function details(): HasMany {
        return $this->hasMany(InvoiceDetail::class);
    }
}
