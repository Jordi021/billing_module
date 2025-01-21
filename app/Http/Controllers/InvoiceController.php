<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public function GenerarPDF($invoiceId = null) {
        if ($invoiceId) {
            $invoice = Invoice::with('details')->findOrFail($invoiceId);
            $pdf = PDF::loadView(
                'livewire.invoices.single-pdf',
                compact('invoice')
            );
            return $pdf->download('invoice-' . $invoice->id . '.pdf');
        }

        $invoices = Invoice::all();
        $pdf = PDF::loadView('livewire.invoices.pdf', compact('invoices'));
        return $pdf->download('report_invoices.pdf');
    }
}
