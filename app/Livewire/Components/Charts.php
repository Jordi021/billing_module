<?php
namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Client;
use App\Models\Invoice;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Charts extends Component {
    public $startDate;
    public $endDate;

    public function mount() {
        if (!$this->startDate) {
            $minDate = Invoice::min('invoice_date');
            $this->startDate = $minDate ? 
                Carbon::parse($minDate)->format('Y-m-d') : 
                Carbon::now()->format('Y-m-d');
        }
        
        if (!$this->endDate) {
            $maxDate = Invoice::max('invoice_date');
            $this->endDate = $maxDate ? 
                Carbon::parse($maxDate)->format('Y-m-d') : 
                Carbon::now()->format('Y-m-d');
        }
    }

    public function render() {
        return view('livewire.components.charts', [
            'data' => $this->getChartData(),
        ]);
    }

    public function getChartData() {
        $query = Invoice::query();

        if ($this->startDate || $this->endDate) {
            $query
                ->when($this->startDate, function ($q) {
                    $startDate = DateHelper::toDatabase($this->startDate);
                    return $q->whereDate('invoice_date', '>=', $startDate);
                })
                ->when($this->endDate, function ($q) {
                    $endDate = DateHelper::toDatabase($this->endDate);
                    return $q->whereDate('invoice_date', '<=', $endDate);
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

        // Ventas totales por mes usando TO_CHAR para PostgreSQL
        $monthlySales = $query
            ->selectRaw(
                "TO_CHAR(invoice_date, 'YYYY-MM') as month, SUM(total) as total_sales"
            )
            ->groupByRaw("TO_CHAR(invoice_date, 'YYYY-MM')")
            ->orderByRaw("TO_CHAR(invoice_date, 'YYYY-MM') ASC")
            ->get()
            ->unique('month')
            ->values();

        // Simplificar la distribución por tipo de pago
        $paymentDistribution = DB::table('invoices')
            ->selectRaw("
                COUNT(CASE WHEN payment_type = 'cash' THEN 1 END) as cash_count,
                COUNT(CASE WHEN payment_type = 'credit' THEN 1 END) as credit_count
            ")
            ->when($this->startDate, function ($query) {
                return $query->whereDate('invoice_date', '>=', DateHelper::toDatabase($this->startDate));
            })
            ->when($this->endDate, function ($query) {
                return $query->whereDate('invoice_date', '<=', DateHelper::toDatabase($this->endDate));
            })
            ->first();

        return [
            'clients' => $clients,
            'monthlySales' => $monthlySales,
            'paymentDistribution' => [
                ['payment_type' => 'cash', 'count' => $paymentDistribution->cash_count],
                ['payment_type' => 'credit', 'count' => $paymentDistribution->credit_count]
            ],
        ];
    }

    public function updated($propertyName) {
        if ($propertyName === 'startDate' || $propertyName === 'endDate') {
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
