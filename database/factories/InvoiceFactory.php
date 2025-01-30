<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;

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
            'client_id' => Client::all()->random()->id,
            'payment_type' => $this->faker->randomElement(['cash', 'credit']),
            'invoice_date' => $this->faker->dateTimeBetween('-3 year', 'now'),
            'total' => 0, //will be calculated later based on invoice_detail
            'note' => $this->faker->sentence(),
        ];
    }

    // Method to add invoice details using local data
    public function withDetails(int $maxProducts = 3) {
        return $this->afterCreating(function (Invoice $invoice) use ($maxProducts) {
            try {
                // Get products from API
                $response = Http::withoutVerifying()->get(
                    'https://seashell-app-9et5v.ondigitalocean.app/api/productos'
                );

                $products = collect($response->json())->map(function (
                    $product
                ) {
                    return [
                        'id' => $product['Product_Id'],
                        'code' => $product['Code'],
                        'title' => $product['Name'],
                        'description' => $product['Description'],
                        'cost' => (float) $product['Cost'],
                        'price' => (float) $product['Price'],
                        'status' => $product['Status'],
                        'stock' => $product['Stock'],
                        'category_id' => $product['Category']['Category_Id'],
                        'category_type' => $product['Category']['Type'],
                        'vat_percentage' => $product['Category']['VAT'],
                    ];
                });

                // Generar un número aleatorio de productos entre 1 y maxProducts
                $numberOfProducts = rand(1, $maxProducts);

                // Take random products without filtering
                $selectedProducts = $products->random(
                    min($numberOfProducts, $products->count())
                );

                $total = 0;

                // Create invoice details
                $selectedProducts->each(function ($product) use (
                    $invoice,
                    &$total
                ) {
                    $quantity = rand(1, 5);
                    $subtotal = $quantity * $product['price'];
                    $vatAmount = $subtotal * ($product['vat_percentage'] / 100);
                    $total += $subtotal + $vatAmount;

                    $invoice->details()->create([
                        'product_id' => $product['id'],
                        'quantity' => $quantity,
                        'unit_price' => $product['price'],
                        'subtotal' => $subtotal,
                        'vat_amount' => $vatAmount,
                    ]);
                });

                // Update invoice total
                $invoice->update(['total' => $total]);
            } catch (\Exception $e) {
                logger()->error('Error in invoice factory:', [
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });
    }
}
