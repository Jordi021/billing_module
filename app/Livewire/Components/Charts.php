<?php
namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class Charts extends Component {
    public $startDate;
    public $endDate;

    public function mount() {
        // Establecer fechas iniciales si no están definidas
        if (!$this->startDate) {
            $this->startDate = Invoice::min('invoice_date');
        }
        if (!$this->endDate) {
            $this->endDate = Invoice::max('invoice_date');
        }
    }

    public function render() {
        return view('livewire.components.charts', [
            'data' => $this->getChartData(),
        ]);
    }

    public function getChartData() {
        $query = Invoice::query();

        // Aplicar filtro de fechas si ambas están definidas
        if ($this->startDate || $this->endDate) {
            $query
                ->when($this->startDate, function ($q) {
                    return $q->where('invoice_date', '>=', $this->startDate);
                })
                ->when($this->endDate, function ($q) {
                    return $q->where('invoice_date', '<=', $this->endDate);
                });
        }

        $clients = $query
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->select(
                'clients.name',
                'clients.last_name',
                DB::raw('SUM(invoices.total) as total_sales'),
                DB::raw('COUNT(invoices.id) as total_invoices')
            )
            ->groupBy('clients.id', 'clients.name', 'clients.last_name')
            ->orderByDesc(DB::raw('SUM(invoices.total)')) 
            ->limit(10)
            ->get();

        // Ventas totales por mes (modificado para ordenar correctamente)
        $monthlySales = $query
            ->selectRaw(
                "TO_CHAR(invoice_date, 'YYYY-MM') as month, SUM(total) as total_sales"
            )
            ->groupByRaw("TO_CHAR(invoice_date, 'YYYY-MM')")
            ->orderByRaw("TO_CHAR(invoice_date, 'YYYY-MM') ASC") // Cambiamos el orden a ASC explícitamente
            ->get()
            ->unique('month')
            ->values(); // Esto reindexará la colección después de unique

        // Distribución por tipo de pago
        $paymentDistribution = $query
            ->select('payment_type', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_type')
            ->get();

        return [
            'clients' => $clients,
            'monthlySales' => $monthlySales,
            'paymentDistribution' => $paymentDistribution,
        ];
    }

    public function updated($propertyName) {
        if ($propertyName === 'startDate' || $propertyName === 'endDate') {
            // Validar que startDate no sea mayor que endDate
            if (
                $this->startDate &&
                $this->endDate &&
                $this->startDate > $this->endDate
            ) {
                if ($propertyName === 'startDate') {
                    $this->endDate = $this->startDate;
                } else {
                    $this->startDate = $this->endDate;
                }
            }

            $this->dispatch('charts-updated', data: $this->getChartData());
        }
    }
}
