<?php

namespace Tests\Feature;

use App\Filament\Imports\EmployeeImporter;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Models\Employee;
use App\Models\User;
use Filament\Actions\ImportAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmployeeImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_list_page_contains_import_action()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ListEmployees::class)
            ->assertActionExists('import');
    }

    public function test_importer_resolves_existing_record_by_email()
    {
        $existingEmployee = Employee::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'position' => 'Developer',
            'salary' => 100000,
        ]);

        $importer = new EmployeeImporter(
            import: new \Filament\Actions\Imports\Models\Import(),
            columnMap: [],
            options: [],
        );

        // Reflection to set data since resolveRecord uses $this->data
        $reflection = new \ReflectionClass($importer);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $property->setValue($importer, ['email' => 'john@example.com']);

        $resolvedRecord = $importer->resolveRecord();

        $this->assertTrue($resolvedRecord->exists);
        $this->assertEquals($existingEmployee->id, $resolvedRecord->id);
    }

    public function test_importer_creates_new_instance_if_not_found()
    {
        $importer = new EmployeeImporter(
            import: new \Filament\Actions\Imports\Models\Import(),
            columnMap: [],
            options: [],
        );

        $reflection = new \ReflectionClass($importer);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $property->setValue($importer, ['email' => 'new@example.com']);

        $resolvedRecord = $importer->resolveRecord();

        $this->assertFalse($resolvedRecord->exists);
    }

    public function test_importer_resolves_existing_record_by_phone()
    {
        $existingEmployee = Employee::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '0987654321', // Unique phone
            'position' => 'Manager',
            'salary' => 120000,
        ]);

        $importer = new EmployeeImporter(
            import: new \Filament\Actions\Imports\Models\Import(),
            columnMap: [],
            options: [],
        );

        $reflection = new \ReflectionClass($importer);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        // Simulate CSV with different email but SAME phone
        $property->setValue($importer, [
            'email' => 'other@example.com',
            'phone' => '0987654321'
        ]);

        $resolvedRecord = $importer->resolveRecord();

        $this->assertTrue($resolvedRecord->exists);
        $this->assertEquals($existingEmployee->id, $resolvedRecord->id);
    }

    public function test_importer_has_phone_validation_rules()
    {
        $columns = EmployeeImporter::getColumns();
        $phoneColumn = collect($columns)->first(fn($column) => $column->getName() === 'phone');

        $this->assertNotNull($phoneColumn);
        $this->assertContains('required', $phoneColumn->getDataValidationRules());
        $this->assertContains('regex:/^(20|21|30)\d{8}$/', $phoneColumn->getDataValidationRules());
    }
}
