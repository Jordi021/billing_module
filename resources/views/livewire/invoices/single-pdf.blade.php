<style>
    body {
        font-family: 'Helvetica', sans-serif;
        background-color: #ffffff;
        margin: 0;
        padding: 20px;
        color: #374151;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        color: #000000;
        font-size: 28px;
        margin: 0;
    }

    .header p {
        font-size: 14px;
        color: #6b7280;
        margin: 5px 0;
    }

    .invoice-info {
        margin-bottom: 20px;
    }

    .invoice-info h2 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #000000;
    }

    .invoice-info p {
        font-size: 14px;
        margin: 2px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    th {
        background-color: #3b82f6;
        color: white;
        padding: 12px 8px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    td {
        padding: 10px 8px;
        font-size: 12px;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .total-summary {
        margin-top: 20px;
        text-align: right;
        font-size: 16px;
    }

    .total-summary p {
        margin: 5px 0;
        color: #000000;
    }
</style>

<div class="header">
    <h1>Factura</h1>
    <p>Fecha de emisión: {{ now()->format('d/m/Y H:i:s') }}</p>
    <p>ID Factura: {{ $invoice->id }}</p>
</div>

<div class="invoice-info">
    <h2>Información del Cliente</h2>
    <p><strong>Nombre:</strong> {{ $invoice->client->name }} {{ $invoice->client->last_name }}</p>
    <p><strong>Tipo de Pago:</strong> {{ $invoice->payment_type }}</p>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y H:i:s') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unit.</th>
            <th>Subtotal</th>
            <th>IVA</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice->details as $detail)
        <tr>
            <td>{{ $detail['code'] ?? 'N/A' }}</td>
            <td>{{ $detail['product_name'] }}</td>
            <td>{{ $detail['quantity'] }}</td>
            <td>${{ number_format($detail['unit_price'], 2) }}</td>
            <td>${{ number_format($detail['subtotal'], 2) }}</td>
            <td>${{ number_format($detail['vat_amount'], 2) }}</td>
            <td>${{ number_format($detail['subtotal'] + $detail['vat_amount'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="total-summary">
    <p><strong>Subtotal General:</strong> ${{ number_format($invoice->details->sum('subtotal'), 2) }}</p>
    <p><strong>IVA Total:</strong> ${{ number_format($invoice->details->sum('vat_amount'), 2) }}</p>
    <p><strong>Total:</strong> ${{ number_format($invoice->total, 2) }}</p>
</div>
