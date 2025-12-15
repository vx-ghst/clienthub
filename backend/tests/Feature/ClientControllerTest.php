<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use PHPUnit\Framework\Attributes\Test;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_clients()
    {
        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_validates_email_when_creating(): void
    {
        $data = [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'email' => 'not-an-email',
        ];

        $response = $this->postJson('/api/clients', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function it_validates_phone_when_creating(): void
    {
        $data = [
            'firstname' => 'John',
            'lastname' => 'Smith',
            'email' => 'john@gmail.com',
            'phone' => '12345',
        ];

        $response = $this->postJson('/api/clients', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    #[Test]
    public function it_can_create_a_client()
    {
        $data = [
            'firstname' => 'Test',
            'lastname'  => 'User',
            'email'     => 'test@example.com',
            'phone'     => '+3212345678',
        ];

        $response = $this->postJson('/api/clients', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'test@example.com']);

        $this->assertDatabaseHas('clients', ['firstname' => 'Test']);
        $this->assertDatabaseHas('clients', ['lastname' => 'User']);
        $this->assertDatabaseHas('clients', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('clients', ['phone' => '+3212345678']);
    }


    #[Test]
    public function it_can_show_a_client()
    {
        $client = Client::factory()->create();

        $response = $this->getJson("/api/clients/{$client->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $client->email]);
    }

    #[Test]
    public function it_validates_email_when_updating(): void
    {
        $client = Client::factory()->create();

        $data = [
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'invalid-email',
        ];

        $response = $this->putJson("/api/clients/{$client->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function it_validates_phone_when_updating(): void
    {
        $client = Client::factory()->create();

        $data = [
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@gmail.com',
            'phone' => 'invalid-phone',
        ];

        $response = $this->putJson("/api/clients/{$client->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    #[Test]
    public function it_can_update_a_client()
    {
        $client = Client::factory()->create();

        $response = $this->putJson("/api/clients/{$client->id}", [
            'firstname' => 'Updated',
            'lastname'  => $client->lastname,
            'email'     => $client->email,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['firstname' => 'Updated']);

        $this->assertDatabaseHas('clients', ['firstname' => 'Updated']);
    }

    #[Test]
    public function it_can_delete_a_client()
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
