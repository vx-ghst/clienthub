<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ContractType;
use App\Enums\ContractStatus;

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
}
