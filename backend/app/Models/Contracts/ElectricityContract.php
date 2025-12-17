<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Contracts\Contract;

class ElectricityContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'ean',
        'power_kw',
        'tariff_code',
        'tariff_price_kwh',
        'tariff_subscription',
        'advance_amount',
        'advance_frequency',
    ];

    protected $casts = [
        'power_kw' => 'decimal:2',
        'tariff_price_kwh' => 'decimal:4',
        'tariff_subscription' => 'decimal:2',
        'advance_amount' => 'decimal:2',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
