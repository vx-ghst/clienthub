<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Contracts\ElectricityContract;
use App\Enums\Contracts\ContractType;
use App\Enums\Contracts\ContractStatus;
use App\Models\Client;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'start_date',
        'end_date',
        'client_id',
    ];

    protected $casts = [
        'type' => ContractType::class,
        'status' => ContractStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Each contract belongs to a client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function electricity(): HasOne
    {
        return $this->hasOne(ElectricityContract::class);
    }
}
