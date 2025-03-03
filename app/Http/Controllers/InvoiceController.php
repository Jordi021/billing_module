<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller {
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a list of invoices.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    /**
     * Generate a PDF report of all invoices.
     *
     * This function retrieves all invoices and their details,
     * loads them into a PDF view, and returns the PDF for download.
     *
     * @return \Illuminate\Http\Response
     */
    public function GenerarPDF(Request $request, $id = null)
    {
        if ($id) {
            $invoice = Invoice::with('details')->findOrFail($id);

            $invoice->update(['is_locked' => true]);
            $products = $this->fetchProducts();
            $invoice->details = $invoice->details->map(function ($detail) use (
                $products
            ) {
                $product = collect($products)->firstWhere(
                    'id',
                    $detail->product_id
                );
                return [
                    'product_id' => $detail->product_id,
                    'code' => $product['code'] ?? 'N/A',
                    'product_name' =>
                        $product['title'] ?? 'Producto #' . $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                    'subtotal' => $detail->subtotal,
                    'vat_amount' => $detail->vat_amount,
                    'vat_percentage' => $product['vat_percentage'] ?? 0,
                ];
            });

            $pdf = PDF::loadView(
                'livewire.invoices.single-pdf',
                compact('invoice')
            );
            return $pdf->download('invoice-' . $invoice->id . '.pdf');
        }

        // Para el reporte general de facturas con filtros avanzados
        $query = Invoice::query();

        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->start_date || $request->end_date) {
            $query->where(function($q) use ($request) {
                if ($request->start_date && $request->end_date) {
                    $q->whereBetween('invoice_date', [
                        $request->start_date . ' 00:00:00',
                        $request->end_date . ' 23:59:59'
                    ]);
                } elseif ($request->start_date) {
                    $q->where('invoice_date', '>=', $request->start_date . ' 00:00:00');
                } elseif ($request->end_date) {
                    $q->where('invoice_date', '<=', $request->end_date . ' 23:59:59');
                }
            });
        }

        if ($request->status !== 'all') {
            $query->whereHas('client', function ($clientQuery) use ($request) {
                $clientQuery->where('status', $request->status);
            });
        }

        if ($request->payment_type !== 'all') {
            $query->where('payment_type', 'ILIKE', '%' . $request->payment_type . '%');
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->get();

        $pdf = PDF::loadView('livewire.invoices.pdf', compact('invoices'));
        return $pdf->download('report_invoices.pdf');
    }

    private function fetchProducts() {
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get(
                    'https://seashell-app-9et5v.ondigitalocean.app/api/productos'
                );

            if ($response->successful()) {
                return collect($response->json())
                    ->map(function ($product) {
                        $category = $product['Category'] ?? [];
                        $vat = $category['VAT'] ?? 0;

                        return [
                            'id' => $product['Product_Id'],
                            'code' => $product['Code'],
                            'title' => $product['Name'],
                            'description' => $product['Description'],
                            'price' => (float) $product['Price'],
                            'stock' => $product['Stock'],
                            'vat_percentage' => $vat,
                            'category_type' =>
                                $category['Type'] ?? 'Sin categoría',
                        ];
                    })
                    ->values()
                    ->toArray();
            }
        } catch (\Exception $e) {
            logger()->error('Error fetching products for PDF:', [
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }
}
