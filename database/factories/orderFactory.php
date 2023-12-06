<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order>
 */
class orderFactory extends Factory
{
    /**
     * $table->id();
            * $table->foreignIdFor(User::class);
           *  $table->text('delivery_address');
           *  $table->foreignIdFor(doctor::class)->nullable();
           *  $table->boolean('is_insured');
            * $table->string('status');
            * $table->string('creator_type');
            * $table->foreignIdFor(pharmacy::class)->nullable();
            * $table->timestamps();
     * Define the model's default state.
     *
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'user_id' => 1,
            'delivery_address' => fake()->address(),
            'doctor_id' => 1,
            'is_insured' => fake()->boolean(),
            'status' => 'New',
            'creator_type' => 'user',
            'pharmacy_id' => 1



        ];
    }
}
