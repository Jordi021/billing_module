<style>
    body {
        font-family: 'Helvetica', sans-serif;
        background-color: #ffffff;
        margin: 0;
        padding: 20px;
    }

    .header {
        text-align: center;
    }

    .header h1 {
        color: #000000;
        font-size: 24px;
        margin: 0;
        margin-bottom: 10px;
    }

    .header .report-info {
        color: #6b7280;
        display: flex;
        width: 100%;
        justify-content: center;
        font-size: 12px;
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
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
    }

    td {
        padding: 10px 8px;
        font-size: 9px;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .page-number {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 10px;
        color: #6b7280;
    }

    .page-number::after {
        content: "Página " counter(page);
    }
</style>

<div class="header">
    <h1>Reporte de Facturas</h1>
    <div class="report-info">
        <p>Fecha de emisión: {{ now()->format('d-m-Y H:i:s') }}</p>
        <p>Total de facturas: {{ $invoices->count() }}</p>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Tipo de Pago</th>
            <th>Fecha</th>
            <th>Nota</th>
            <th>Detalles</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoices as $invoice)
        <tr>
            <td>{{ $invoice->id }}</td>
            <td>{{ $invoice->client->name }} {{ $invoice->client->last_name }}</td>
            <td>{{ $invoice->payment_type }}</td>
            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y H:i:s') }}</td>
            <td>{{ $invoice->note }}</td>
            <td>
                <ul>
                    @foreach ($invoice->details as $detail)
                    <li>ID Producto: {{ $detail->product_id }} ({{ $detail->quantity }} x {{ $detail->unit_price }}) - {{ $detail->subtotal }}</li>
                    @endforeach
                </ul>
            </td>
            <td>{{ $invoice->total }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="page-number"></div>