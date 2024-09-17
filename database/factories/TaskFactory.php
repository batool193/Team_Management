<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\TaskStatus;
use App\Enums\TaskPriorty;
use Carbon\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'priority' => $this->faker->randomElement(TaskPriorty::values()),
            'due_date' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)),
            'status' => $this->faker->randomElement(TaskStatus::values()),
            'notes' => $this->faker->optional()->paragraph,
        ];
    }
}
