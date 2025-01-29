<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Client extends Model implements Auditable {
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'last_name',
        'birth_date',
        'client_type',
        'address',
        'phone',
        'email',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // public function getClientTypeAttribute($value) {
    //     $translations = [
    //         "Cash" => "Contado",
    //         "Credit" => "Crédito",
    //     ];
    //     return $translations[$value] ?? $value;
    // }

    public function getBirthDateAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function invoices(): HasMany {
        return $this->hasMany(Invoice::class, 'client_id');
    }
}
