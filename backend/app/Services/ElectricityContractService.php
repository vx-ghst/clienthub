<?php

namespace App\Services;

use App\Models\Contracts\ElectricityContract;
use Illuminate\Support\Facades\DB;

class ElectricityContractService
{
    public function create(array $data): ElectricityContract
    {
        return DB::transaction(function () use ($data) {
            return ElectricityContract::create($data);
        });
    }
}
