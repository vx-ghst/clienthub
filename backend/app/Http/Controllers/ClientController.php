<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Http\JsonResponse;

/**
 * Class ClientController
 *
 * REST controller for the Client resource.
 * Provides full CRUD operations exposed via JSON API.
 *
 * Associated routes (via Route::apiResource):
 * GET    /api/clients
 * GET    /api/clients/{id}
 * POST   /api/clients
 * PUT    /api/clients/{id}
 * DELETE /api/clients/{id}
 *
 * Responses are JSON serialized.
 * HTTP status codes follow REST standards (200, 201, 204, 404).
 *
 */
class ClientController extends Controller
{
    /**
     * GET /api/clients
     *
     * Retrieves all clients.
     *
     * @return JsonResponse List of all clients
     */
    public function index(): JsonResponse
    {
        return response()->json(Client::all());
    }

    /**
     * POST /api/clients
     *
     * Creates a new client.
     * Only the following fields are allowed for mass assignment: firstname, lastname, email.
     *
     * @param Request $request Client-submitted data
     * @return JsonResponse Newly created client with 201 status
     */
    public function store(Request $request): JsonResponse
    {
        $client = Client::create($request->only([
            'firstname',
            'lastname',
            'email',
        ]));

        return response()->json($client, 201);
    }

    /**
     * GET /api/clients/{id}
     *
     * Retrieves a client by ID.
     * Returns 404 if the client does not exist.
     *
     * @param int $id Client ID
     * @return JsonResponse Found client or error message
     */
    public function show(int $id): JsonResponse
    {
        $client = Client::find($id);

        if (! $client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    /**
     * PUT /api/clients/{id}
     *
     * Updates an existing client.
     * Returns 404 if the client does not exist.
     *
     * @param Request $request Updated client data
     * @param int $id Client ID
     * @return JsonResponse Updated client or error message
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $client = Client::find($id);

        if (! $client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $client->update($request->only([
            'firstname',
            'lastname',
            'email',
        ]));

        return response()->json($client);
    }

    /**
     * DELETE /api/clients/{id}
     *
     * Deletes an existing client.
     * Returns 204 No Content if deletion succeeds.
     * Returns 404 if the client does not exist.
     *
     * @param int $id Client ID
     * @return JsonResponse Empty response or error message
     */
    public function destroy(int $id): JsonResponse
    {
        $client = Client::find($id);

        if (! $client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $client->delete();

        return response()->json(null, 204);
    }
}
