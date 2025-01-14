<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        Client::create([
            'id' => '1234567890',
            'name' => 'John',
            'last_name' => 'Doe',
            'birth_date' => '1980-01-01',
            'client_type' => 'Cash',
            'address' => '123 Main Street',
            'phone' => '0967451554',
            'email' => 'john.doe@example.com',
            'status' => true,
        ]);

        $this->call([
            ClientSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
