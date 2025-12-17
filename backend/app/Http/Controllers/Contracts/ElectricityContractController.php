<?php

namespace App\Http\Controllers\Contracts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Contracts\ElectricityContract;
use App\Http\Requests\Contracts\StoreElectricityContractRequest;
use App\Services\ElectricityContractService;
use App\Http\Requests\Contracts\UpdateElectricityContractRequest;

class ElectricityContractController extends Controller
{
    public function __construct(
        protected ElectricityContractService $electricityContractService
    ) {}

    /**
     * GET /api/electricity-contracts
     * Retourne tous les contrats électricité
     */
    public function index(): JsonResponse
    {
        return response()->json(ElectricityContract::with('contract')->get());
    }

    /**
     * POST /api/electricity-contracts
     * Crée un nouveau contrat électricité
     */
    public function store(StoreElectricityContractRequest $request): JsonResponse
    {
        $data = $request->validated();

        $electricityContract = $this->electricityContractService->create($data);

        return response()->json($electricityContract, 201);
    }

    /**
     * GET /api/electricity-contracts/{id}
     * Récupère un contrat électricité spécifique
     */
    public function show(int $id): JsonResponse
    {
        $contract = ElectricityContract::with('contract')->find($id);

        if (!$contract) {
            return response()->json(['message' => 'Electricity contract not found'], 404);
        }

        return response()->json($contract);
    }

    /**
     * PUT /api/electricity-contracts/{id}
     * Met à jour un contrat électricité spécifique
     */
    public function update(UpdateElectricityContractRequest $request, ElectricityContract $electricityContract): JsonResponse
    {
        $data = $request->validated();
        $updated = $this->electricityContractService->update($electricityContract, $data);

        return response()->json($updated);
    }

    /**
     * DELETE /api/electricity-contracts/{id}
     * Supprime un contrat électricité
     */
    public function destroy(int $id): JsonResponse
    {
        $contract = ElectricityContract::find($id);

        if (!$contract) {
            return response()->json(['message' => 'Electricity contract not found'], 404);
        }

        $contract->delete();

        return response()->json(null, 204);
    }
}
