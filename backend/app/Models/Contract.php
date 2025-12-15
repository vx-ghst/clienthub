<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Client;
use App\Enums\ContractType;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Illuminate\Support\MessageBag;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'start_date',
        'end_date',
        'client_id'
    ];

    /**
     * Each contract belongs to one client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get type as enum.
     */
    public function getType(): ContractType
    {
        return ContractType::from($this->attributes['type']);
    }

    /**
     * Set type with validation via ContractType enum.
     *
     * @param ContractType|string $type
     * @throws InvalidArgumentException|ValidationException
     */
    public function setType(ContractType|string $type): void
    {
        // Convert string or enum to valid enum value
        $value = self::resolveContractType($type);

        // Validate uniqueness for the client using central method
        if ($this->client_id && !$this->exists) {
            self::validateUniqueTypeForClient($this->client_id, $value);
        }

        $this->attributes['type'] = $value;
    }

    /**
     * Check if client already has contract of given type.
     */
    private static function existsForClient(int $clientId, ContractType|string $type): bool
    {
        $value = self::resolveContractType($type);

        return self::where('client_id', $clientId)
            ->where('type', $value)
            ->exists();
    }

    public static function validateUniqueTypeForClient(int $clientId, string $type): void
    {
        if (self::existsForClient($clientId, $type)) {
            throw ValidationException::withMessages([
                'type' => "Client already has a contract of type {$type}."
            ]);
        }
    }

    /**
     * Convert input to a valid ContractType value.
     *
     * @param ContractType|string $type
     * @return string
     * @throws \InvalidArgumentException
     */
    private static function resolveContractType(ContractType|string $type): string
    {
        $value = $type instanceof ContractType ? $type->value : $type;

        if (! ContractType::isValid($value)) {
            throw new \InvalidArgumentException("Invalid contract type: {$value}");
        }

        return $value;
    }
}
