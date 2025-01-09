<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Invoice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory {
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            "client_id" => Client::all()->random()->id,
            "payment_type" => $this->faker->randomElement(["cash", "credit"]),
            "invoice_date" => $this->faker->dateTimeBetween("-1 year", "now"),
            "total" => 0, //will be calculated later based on invoice_detail
            "note" => $this->faker->sentence(),
        ];
    }

    // Method to add invoice details using local data
    public function withDetails(int $numberOfProducts = 3) {
        return $this->afterCreating(function (Invoice $invoice) use (
            $numberOfProducts
        ) {
            // Get products from local JSON file
            $products = collect(
                json_decode(
                    file_get_contents(database_path('seeders/fake_products.json')),
                    true
                )
            );

            //dd($products->count());
            // Take random products
            $selectedProducts = $products->random($numberOfProducts);

            $total = 0;

            // Create invoice details
            $selectedProducts->each(function ($product) use (
                $invoice,
                &$total
            ) {
                $quantity = rand(1, 5);
                $subtotal = $quantity * $product["price"];
                $total += $subtotal;

                $invoice->details()->create([
                    "product_id" => $product["id"],
                    "product_name" => $product["title"],
                    "quantity" => $quantity,
                    "unit_price" => $product["price"],
                    "subtotal" => $subtotal,
                ]);
            });

            // Update invoice total
            $invoice->update(["total" => $total]);
        });
    }
}
