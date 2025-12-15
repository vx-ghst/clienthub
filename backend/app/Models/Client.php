<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function hasContractType(string $type): bool
    {
        return $this->contracts()->where('type', $type)->exists();
    }
}
