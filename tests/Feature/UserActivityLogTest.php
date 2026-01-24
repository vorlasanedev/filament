<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_creation_logs_activity()
    {
        $user = User::factory()->create([
            'name' => 'Soulivanh',
            'email' => 'soulivanh@example.com'
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'description' => 'created',
        ]);
        
        $activity = Activity::latest()->first();
        $this->assertEquals('created', $activity->description);
        $this->assertEquals('soulivanh@example.com', $activity->properties['attributes']['email']);
    }

    public function test_user_update_logs_activity()
    {
        $user = User::factory()->create();
        
        $user->update(['name' => 'New Name']);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'description' => 'updated',
        ]);


        $activity = Activity::where('description', 'updated')->first();
        $this->assertNotNull($activity, 'Updated activity not found');
        $this->assertEquals('updated', $activity->description);
        $this->assertEquals('New Name', $activity->properties['attributes']['name']);
        $this->assertArrayHasKey('old', $activity->properties);
    }
}
