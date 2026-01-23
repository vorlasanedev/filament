<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Filament\Resources\UserResource;

use Illuminate\Foundation\Testing\RefreshDatabase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;
  
    public function test_can_render_user_resource_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Assuming the user has access. Filament permissions might be required.
        // For basic Filament, any authenticated user might access if unrelated policies are not set.

        $response = $this->get(UserResource::getUrl('index'));
        $response->assertStatus(200);
    }

    public function test_can_render_user_resource_create_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(UserResource::getUrl('create'));
        $response->assertStatus(200);
    }
}
