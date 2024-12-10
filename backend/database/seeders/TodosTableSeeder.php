<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Persistence\Eloquent\Todo\TodoModel;
use Carbon\Carbon;

class TodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the todos table (if needed) while respecting foreign key constraints
        TodoModel::query()->delete();

        $currentDate = Carbon::now();

        // Generate todos for 90 days (3 months)
        for ($day = 0; $day < 90; $day++) {
            // Generate 3 to 5 todos for the current day
            $todosCount = random_int(3, 5);
            $completedCount = random_int(3, 4); // 3 to 4 todos completed
            $completedTodos = 0;

            for ($i = 0; $i < $todosCount; $i++) {
                TodoModel::create([
                    'title' => 'Todo for ' . $currentDate->toDateString() . ' #' . ($i + 1),
                    'description' => 'This is todo item #' . ($i + 1) . ' for ' . $currentDate->toDateString(),
                    'isCompleted' => $completedTodos < $completedCount ? true : (bool)random_int(0, 1),
                    'image' => 'default.jpg',
                    'created_at' => $currentDate->toDateTimeString(),
                    'updated_at' => $currentDate->toDateTimeString(),
                ]);

                // Increment the completed todos counter if marked as completed
                if ($completedTodos < $completedCount) {
                    $completedTodos++;
                }
            }

            // Move to the previous day
            $currentDate->subDay();
        }
    }
}
