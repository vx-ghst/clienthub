<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Contract;
use App\Models\Client;
use App\Enums\ContractType;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_allows_creating_contract_with_valid_enum()
    {
        $client = Client::factory()->create();

        $contract = new Contract([
            'client_id' => $client->id,
            'type' => ContractType::Electricity,
            'status' => 'active',
            'start_date' => now(),
        ]);

        $contract->save();

        $this->assertDatabaseHas('contracts', [
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
        ]);
    }

    #[Test]
    public function it_throws_exception_for_invalid_contract_type()
    {
        $client = Client::factory()->create();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid contract type');

        // On crée le contrat sans le type pour utiliser le setter
        $contract = new Contract([
            'client_id' => $client->id,
            'status' => 'active',
            'start_date' => now(),
        ]);

        // Setter qui va valider l'enum et lancer l'exception
        $contract->setType('invalid_type');

        // Si on arrive ici, MySQL n’est jamais appelé car l’exception est lancée avant save()
        $contract->save();
    }

    #[Test]
    public function it_prevents_duplicate_contract_type_for_same_client()
    {
        $client = Client::factory()->create();

        // Premier contrat valide
        $contract1 = new Contract([
            'client_id' => $client->id,
            'status' => 'active',
            'start_date' => now(),
        ]);
        $contract1->setType(ContractType::Gas);
        $contract1->save();

        // Deuxième contrat du même type → doit lancer ValidationException
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->expectExceptionMessage('Client already has a contract of type gas.');

        $contract2 = new Contract([
            'client_id' => $client->id,
            'status' => 'active',
            'start_date' => now(),
        ]);

        // Validation via setter avant save
        $contract2->setType(ContractType::Gas);

        $contract2->save(); // jamais atteint si exception lancée correctement
    }
}
