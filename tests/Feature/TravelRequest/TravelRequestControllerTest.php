<?php

namespace Tests\Feature\TravelRequest\Controllers;

use App\Domain\TravelRequest\Models\TravelRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class TravelRequestControllerTest extends TestCase
{
    use WithoutMiddleware;

    protected $travelRequest, $route;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->route = '/api/travel/';
    }

    private function prepareEnvironment(): void
    {
        $this->travelRequest = TravelRequest::factory()->create()->toArray();
    }

    private function getPayloadTravelRequest(array $body = []): array
    {
        return array_merge(
            [
                'id' => 1,
                'applicant_name' => 'Pasquali Solution',
                'destination' => 'New York',
                'departure_date' => '2025-06-15',
                'return_date' => '2025-06-20',
                'status' => 'aprovado',
            ],
            $body
        );
    }

    public function testIndex()
    {
        $response = $this->getJson($this->route . '/list');

        $response->json();

        $response->assertStatus(Response::HTTP_OK);
    }


    public function testStore()
    {
        $this->prepareEnvironment();
        $data = $this->getPayloadTravelRequest();
        $response = $this->post($this->route . 'create', $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $travelRequest = TravelRequest::factory()->create([
            'user_id' => $user->id
        ]);

        $data = [
            'travelId' => $travelRequest->getKey(),
            'destination' => 'Rio de Janeiro',
            'status' => 'aprovado',
        ];

        $response = $this->putJson($this->route . 'update', $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Solicitação de viagem atualizada com sucesso!']);

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travelRequest->getKey(),
            'destination' => 'Rio de Janeiro',
            'status' => 'aprovado',
        ]);
    }

    public function testAprovar()
    {
        // Arrange
        $admin = User::factory()->create();
        $requester = User::factory()->create();
        $travelRequest = TravelRequest::factory()->create([
            'user_id' => $requester->id,
            'status' => 'solicitado',
        ]);

        $requestData = [
            'travelId' => $travelRequest->id,
        ];

        // Act
        $response = $this->actingAs($admin, 'api')
            ->putJson(route('travel.aprovar'), $requestData);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'mensagem' => 'Status aprovado com sucesso!'
            ]);

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travelRequest->id,
            'status' => 'aprovado',
        ]);
    }

    public function testCancelar()
    {
        // Arrange
        $admin = User::factory()->create();
        $requester = User::factory()->create();
        $travelRequest = TravelRequest::factory()->create([
            'user_id' => $requester->id,
            'status' => 'solicitado',
        ]);

        $requestData = [
            'travelId' => $travelRequest->id,
        ];

        // Act
        $response = $this->actingAs($admin, 'api')
            ->putJson(route('travel.cancelar'), $requestData);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'mensagem' => 'Status foi cancelado com sucesso.'
            ]);

        $this->assertDatabaseHas('travel_requests', [
            'id' => $travelRequest->id,
            'status' => 'cancelado',
        ]);
    }
}
