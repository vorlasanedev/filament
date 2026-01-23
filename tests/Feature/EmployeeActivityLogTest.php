<?php

namespace Tests\Feature;

use App\Models\Employee;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeActivityLogTest extends TestCase
{
    // use RefreshDatabase; // Commented out to avoid wiping existing data if the user prefers, but safe to use in tests usually. Using it to be safe.
    use RefreshDatabase;

    public function test_creating_employee_logs_activity()
    {
        $employee = Employee::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'position' => 'Developer',
            'salary' => 50000,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'description' => 'created',
        ]);
    }

    public function test_updating_employee_logs_activity()
    {
        $employee = Employee::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'position' => 'Developer',
            'salary' => 50000,
        ]);

        $employee->update(['first_name' => 'Updated Name']);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Employee::class,
            'subject_id' => $employee->id,
            'description' => 'updated',
        ]);

        // Verify specific field change is logged
        $activity = Activity::where('subject_type', Employee::class)
            ->where('subject_id', $employee->id)
            ->where('description', 'updated')
            ->latest()
            ->first();

        $this->assertArrayHasKey('first_name', $activity->properties['attributes']);
        $this->assertEquals('Updated Name', $activity->properties['attributes']['first_name']);
        $this->assertArrayHasKey('first_name', $activity->properties['old']);
        $this->assertEquals('Test', $activity->properties['old']['first_name']);
    }
}
