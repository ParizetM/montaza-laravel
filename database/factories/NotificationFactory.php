<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'role_id' => $this->faker->numberBetween(1, 4),
            'type' => 'system',
            'data' => json_encode([
                'title' => $this->faker->sentence,
                'message' => $this->faker->paragraph,
                'action' => $this->faker->word,
            ]),
            'read' => $this->faker->boolean,
        ];
    }
}
