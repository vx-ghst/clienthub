<?php

namespace App\Http\Controllers\Contracts;

use App\Http\Controllers\Controller;
use App\Models\Contracts\Contract;
use App\Http\Requests\Contracts\StoreContractRequest;
use App\Http\Resources\Contracts\ContractResource;
use App\Http\Requests\Contracts\UpdateContractRequest;
use Illuminate\Http\JsonResponse;

class ContractController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ContractResource::collection(Contract::all()));
    }

    public function store(StoreContractRequest $request): JsonResponse
    {
        $contract = Contract::create($request->validated());
        return response()->json(new ContractResource($contract), 201);
    }

    public function show(int $id): JsonResponse
    {
        $contract = Contract::findOrFail($id);
        return response()->json(new ContractResource($contract));
    }


    public function update(UpdateContractRequest $request, int $id)
    {
        $contract = Contract::findOrFail($id);
        $contract->update($request->validated());
        return response()->json($contract);
    }


    public function destroy(int $id): JsonResponse
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();
        return response()->json(null, 204);
    }

    public function listByClient(int $clientId): JsonResponse
    {
        $contracts = Contract::where('client_id', $clientId)->get();
        return response()->json(ContractResource::collection($contracts));
    }

    public function storeForClient(StoreContractRequest $request, int $clientId): JsonResponse
    {
        $data = $request->validated();
        $data['client_id'] = $clientId;
        $contract = Contract::create($data);
        return response()->json(new ContractResource($contract), 201);
    }
}
