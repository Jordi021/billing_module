<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $desiredCount = 42;
        $actualCreated = 0;

        // Intentar crear las facturas una por una
        for ($i = 0; $i < $desiredCount; $i++) {
            try {
                $invoice = Invoice::factory()->withDetails()->create();
                if ($invoice->exists) {
                    $actualCreated++;
                }
            } catch (\Exception $e) {
                // Si hay un error, probablemente no hay suficiente stock
                break;
            }
        }

        // Mostrar información sobre las facturas creadas
        if ($actualCreated < $desiredCount) {
            $this->command->info("Solo se pudieron crear $actualCreated de $desiredCount facturas debido a limitaciones de stock.");
        } else {
            $this->command->info("Se crearon exitosamente $actualCreated facturas.");
        }
    }
}
