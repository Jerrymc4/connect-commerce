<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Address;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 registered customers with addresses and payment methods
        Customer::factory()
            ->count(20)
            ->create()
            ->each(function ($customer) {
                // Create 1-3 addresses for each customer
                $addressCount = rand(1, 3);
                for ($i = 0; $i < $addressCount; $i++) {
                    $isDefault = $i === 0; // Make the first address default
                    $type = $i === 0 ? 'both' : (rand(0, 1) ? 'shipping' : 'billing');
                    
                    $customer->addresses()->create([
                        'address_line_1' => fake()->streetAddress(),
                        'address_line_2' => rand(0, 1) ? fake()->secondaryAddress() : null,
                        'city' => fake()->city(),
                        'state' => fake()->state(),
                        'zipcode' => fake()->postcode(),
                        'country' => fake()->country(),
                        'is_default' => $isDefault,
                        'type' => $type,
                    ]);
                }
                
                // Add 1-2 payment methods for each customer
                $paymentCount = rand(1, 2);
                for ($i = 0; $i < $paymentCount; $i++) {
                    $isDefault = $i === 0; // Make the first payment method default
                    
                    // Card types for demo
                    $cardTypes = ['visa', 'mastercard', 'amex', 'discover'];
                    $cardType = $cardTypes[array_rand($cardTypes)];
                    
                    $customer->paymentMethods()->create([
                        'provider' => 'stripe',
                        'payment_type' => 'credit_card',
                        'token_id' => 'tok_' . Str::random(24),
                        'card_type' => $cardType,
                        'last_four' => fake()->numerify('####'),
                        'expiry_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
                        'expiry_year' => (string) (date('Y') + rand(1, 5)),
                        'cardholder_name' => $customer->full_name,
                        'is_default' => $isDefault,
                    ]);
                }
            });
            
        // Create 5 guest customers without addresses or payment methods
        Customer::factory()
            ->count(5)
            ->guest()
            ->create();
    }
} 