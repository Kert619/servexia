<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
#[UseModel(Employee::class)]
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_hire_date' => now(),
            'employee_comission_rate' => fake()->numberBetween(500, 5000),
        ];
    }

    public function available(): static
    {
        return $this->state(fn() => [
            'employee_status' => EmployeeStatus::AVAILABLE->value,
        ]);
    }

    public function notAvailable(): static
    {
        return $this->state(fn() => [
            'employee_status' => EmployeeStatus::NOT_AVAILABLE->value,
        ]);
    }
}
