<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\File;
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
            'payment_type' => $this->faker->randomElement(['Cash', 'Credit']),
            'invoice_date' => $this->faker->dateTimeBetween(
                now()->subYears(4),
                'now'
            ),
            'total' => 0,
            'note' => $this->faker->sentence(),
        ];
    }

    // Method to add invoice details using local data
    public function withDetails(int $minProducts = 2, int $maxProducts = 5) {
        return $this->afterCreating(function (Invoice $invoice) use (
            $minProducts,
            $maxProducts
        ) {
            try {
                // Get products from API
                $response = Http::withoutVerifying()->get(
                    'https://seashell-app-9et5v.ondigitalocean.app/api/productos'
                );

                // Get products from local file
                // $path = database_path('seeders/products.json');
                // $products = json_decode(File::get($path), true);

                // Filter and map products with stock
                $products = collect($response->json())
                    ->filter(function ($product) {
                        return $product['Status'] && $product['Stock'] > 0;
                    })
                    ->map(function ($product) {
                        return [
                            'id' => $product['Product_Id'],
                            'code' => $product['Code'],
                            'title' => $product['Name'],
                            'description' => $product['Description'],
                            'cost' => (float) $product['Cost'],
                            'price' => (float) $product['Price'],
                            'status' => $product['Status'],
                            'stock' => $product['Stock'],
                            'category_id' =>
                                $product['Category']['Category_Id'],
                            'category_type' => $product['Category']['Type'],
                            'vat_percentage' => $product['Category']['VAT'],
                        ];
                    });

                // If we don't have enough products with stock, delete the invoice and return
                if ($products->count() < $minProducts) {
                    $invoice->delete();
                    return;
                }

                // Generate random number of products (mostly 2-5, occasionally 1)
                $numberOfProducts =
                    rand(1, 100) > 10 ? rand($minProducts, $maxProducts) : 1;

                // Take random products from available stock
                $selectedProducts = $products->random(
                    min($numberOfProducts, $products->count())
                );

                $total = 0;

                // Create invoice details
                $selectedProducts->each(function ($product) use (
                    $invoice,
                    &$total
                ) {
                    // Ensure we don't exceed available stock
                    $maxQuantity = min(5, $product['stock']);
                    $quantity = rand(1, $maxQuantity);

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
                $invoice->delete(); // Delete invoice if there's an error
                throw $e;
            }
        });
    }
}
