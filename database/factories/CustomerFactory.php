<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'phone' => fake()->phoneNumber(),
            'status' => 'active',
            'accepts_marketing' => fake()->boolean(70),
            'is_guest' => false,
            'customer_group' => 'default',
            'remember_token' => Str::random(10),
            'preferences' => null,
            'currency' => 'USD',
            'locale' => 'en',
        ];
    }

    /**
     * Indicate that the customer is a guest.
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_guest' => true,
            'password' => null,
            'email_verified_at' => null,
            'remember_token' => null,
        ]);
    }

    /**
     * Indicate that the customer's email is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Add a random preferred currency.
     */
    public function randomCurrency(): static
    {
        $currencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD'];
        
        return $this->state(fn (array $attributes) => [
            'currency' => fake()->randomElement($currencies),
        ]);
    }
} 