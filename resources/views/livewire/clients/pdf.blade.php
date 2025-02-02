<style>
    body {
        font-family: 'Helvetica', sans-serif;
        background-color: #ffffff;
        margin: 0;
        padding: 20px;
    }

    .header {
        text-align: center;
        /* margin-bottom: 30px;
        border-bottom: 2px solid #3b82f6;
        padding-bottom: 20px; */
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

    .status-active {
        color: #059669;
        font-weight: bold;
    }

    .status-inactive {
        color: #dc2626;
        font-weight: bold;
    }

    .client-type {
        font-weight: bold;
    }

    .credit {
        color: #7c3aed;
    }

    .cash {
        color: #0891b2;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 10px;
        color: #6b7280;
    }

    @page {
        margin: 50px 25px;
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
    <h1>Reporte de Clientes</h1>
    <div class="report-info">
        <p>Fecha de emisión: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total de clientes: {{ $clients->count() }}</p>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Fecha de Nacimiento</th>
            <th>Tipo</th>
            <th>Dirección</th>
            <th>Contacto</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->name }} {{ $client->last_name }}</td>
            <td>{{ \Carbon\Carbon::parse($client->birth_date)->format('d/m/Y') }}</td>
            <td class="client-type {{ $client->client_type == 'Credit' ? 'credit' : 'cash' }}">
                {{ $client->client_type }}
            </td>
            <td>{{ $client->address }}</td>
            <td>
                {{ $client->phone }}<br>
                <small>{{ $client->email }}</small>
            </td>
            <td class="status-{{ $client->status ? 'active' : 'inactive' }}">
                {{ $client->status ? 'Active' : 'Inactive' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="page-number"></div>
