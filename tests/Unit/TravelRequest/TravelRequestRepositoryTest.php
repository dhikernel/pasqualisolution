<?php

namespace Tests\Unit\TravelRequest;

use App\Domain\TravelRequest\Models\TravelRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class TravelRequestRepositoryTest extends TestCase
{
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new \App\Domain\TravelRequest\Repositories\TravelRequestRepository();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_status_aprovar_returns_error_when_user_tries_to_approve_own_request()
    {
        // Arrange
        $user = User::factory()->make(['id' => 4]);
        Auth::shouldReceive('user')->andReturn($user);

        $travelRequestData = ['travelId' => 4];
        $travelRequestMock = Mockery::mock(TravelRequest::class);
        $travelRequestMock->shouldReceive('where')->with('user_id', 4)->andReturnSelf();
        $travelRequestMock->shouldReceive('first')->andReturn($travelRequestMock);

        $this->app->instance(TravelRequest::class, $travelRequestMock);

        // Act
        $result = $this->repository->statusAprovar($travelRequestData);

        // Assert
        $this->assertEquals([
            'mensagem' => 'Você não está autorizado a atualizar o status da sua própria solicitação de viagem ou a solicitação não existe!'
        ], $result);
    }

}
