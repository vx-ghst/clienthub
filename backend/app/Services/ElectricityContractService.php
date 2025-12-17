<?php

namespace App\Services;

use App\Models\Contracts\ElectricityContract;
use App\Models\Contracts\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Enums\Contracts\ContractType;

class ElectricityContractService
{
    /**
     * Crée un contrat électricité en DB.
     *
     * @param array $data Données validées du FormRequest
     * @return ElectricityContract
     * @throws ValidationException
     */
    public function create(array $data): ElectricityContract
    {
        return DB::transaction(function () use ($data) {

            // Vérifier que le contract_id correspond à un contrat de type 'electricity'
            $contract = Contract::find($data['contract_id']);
            if (!$contract || $contract->type !== ContractType::Electricity) {
                throw ValidationException::withMessages([
                    'contract_id' => 'Contract must exist and be of type electricity.'
                ]);
            }

            // Vérifier qu’il n’y a pas déjà un ElectricityContract pour ce contract_id
            if (ElectricityContract::where('contract_id', $data['contract_id'])->exists()) {
                throw ValidationException::withMessages([
                    'contract_id' => 'This contract already has an electricity contract.'
                ]);
            }

            // Création du contrat électricité
            return ElectricityContract::create($data);
        });
    }

    /**
     * Met à jour un contrat électricité existant.
     *
     * @param ElectricityContract $electricityContract
     * @param array $data
     * @return ElectricityContract
     */
    public function update(ElectricityContract $electricityContract, array $data): ElectricityContract
    {
        return DB::transaction(function () use ($electricityContract, $data) {
            $electricityContract->update($data);
            return $electricityContract;
        });
    }

    /**
     * Supprime un contrat électricité.
     *
     * @param ElectricityContract $electricityContract
     * @return void
     */
    public function delete(ElectricityContract $electricityContract): void
    {
        DB::transaction(function () use ($electricityContract) {
            $electricityContract->delete();
        });
    }
}
