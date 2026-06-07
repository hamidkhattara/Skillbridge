<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class LoginRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_boss_is_routed_to_root(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        // Resolve the response class directly from the container
        $response = app(LoginResponse::class)->toResponse(request());

        $this->assertEquals(url('/'), $response->getTargetUrl());
    }

    public function test_coach_is_routed_to_coach_panel(): void
    {
        $user = User::factory()->create(['role' => 'coach']);
        $this->actingAs($user);

        $response = app(LoginResponse::class)->toResponse(request());

        $this->assertEquals(url('/coach'), $response->getTargetUrl());
    }
}