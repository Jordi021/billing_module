<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Table extends Component {
    use WithPagination;

    //advanced filters
    public $client_id = '';
    public $start_date = '';
    public $end_date = '';
    public $report_status = 'all';
    public $report_payment_type = 'all';

    public $search = '';
    public $status = 'all';
    public $payment_type = 'all';

    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'status' => ['except' => 'all', 'as' => 'st'],
        'payment_type' => ['except' => 'all', 'as' => 'ct'],
    ];
    protected $paginationTheme = 'tailwind';

    public function updated($propertyName) {
        if (in_array($propertyName, ['search', 'status', 'payment_type'])) {
            $this->resetPage();
        }
    }

    #[On('reset-basic-filters')]
    public function resetBasicFilters() {
        $this->reset(['search', 'status', 'payment_type']);
        $this->dispatch('reset-filters');
    }

    #[On('invoice-created/updated')]
    public function refresh() {
        $this->resetPage();
    }

    #[On('filter-updated')]
    public function applyFilter($filters) {
        $this->search = $filters['search'];
        $this->status = $filters['status'];
        $this->payment_type = $filters['payment_type'];
        $this->resetPage();
    }

    #[On('advance-filter-updated')]
    public function applyAdvanceFilter($filters) {
        $this->client_id = $filters['client_id'];
        $this->start_date = $filters['start_date'];
        $this->end_date = $filters['end_date'];
        $this->report_status = $filters['status'];
        $this->report_payment_type = $filters['payment_type'];
    }

    #[On('invoice-locked')]
    public function handleInvoiceLocked() {
        $this->refresh();
    }

    #[On('get-current-filters')]
    public function sendCurrentFilters() {
        $this->dispatch('current-filters', [
            'client_id' => $this->client_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->report_status,
            'payment_type' => $this->report_payment_type,
        ]);
    }

    public function render() {
        $query = Invoice::query();
        $advancedFiltersActive =
            $this->client_id ||
            $this->start_date ||
            $this->end_date ||
            $this->report_status !== 'all' ||
            $this->report_payment_type !== 'all';

        // Add client_id filter
        if ($this->client_id) {
            $query->where('client_id', $this->client_id);
        }

        // Modify date range filter to handle datetime
        if ($this->start_date || $this->end_date) {
            $query->where(function ($q) {
                if ($this->start_date && $this->end_date) {
                    $q->whereBetween('invoice_date', [
                        $this->start_date . ' 00:00:00',
                        $this->end_date . ' 23:59:59',
                    ]);
                } elseif ($this->start_date) {
                    $q->where(
                        'invoice_date',
                        '>=',
                        $this->start_date . ' 00:00:00'
                    );
                } elseif ($this->end_date) {
                    $q->where(
                        'invoice_date',
                        '<=',
                        $this->end_date . ' 23:59:59'
                    );
                }
            });
        }

        if ($this->search && !$advancedFiltersActive) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                // Búsqueda directa en la tabla de facturas
                $q->where('id', 'ILIKE', $searchTerm)
                    ->orWhere('invoice_date', 'ILIKE', $searchTerm)
                    ->orWhere('total', 'ILIKE', $searchTerm)
                    ->orWhere('note', 'ILIKE', $searchTerm)
                    // Búsqueda en la relación con clientes
                    ->orWhereHas('client', function ($clientQuery) use (
                        $searchTerm
                    ) {
                        $clientQuery->where(function ($q) use ($searchTerm) {
                            $q->where('id', 'ILIKE', $searchTerm)
                                ->orWhere('name', 'ILIKE', $searchTerm)
                                ->orWhere('last_name', 'ILIKE', $searchTerm)
                                ->orWhereRaw(
                                    "CONCAT(name, ' ', last_name) ILIKE ?",
                                    [$searchTerm]
                                )
                                ->orWhere('address', 'ILIKE', $searchTerm)
                                ->orWhere('email', 'ILIKE', $searchTerm);
                        });
                    });
            });
        }

        if ($this->status !== 'all' && !$advancedFiltersActive) {
            logger('Client status in invoice:', [
                'status invoice' => $this->status,
            ]);
            $query->whereHas('client', function ($clientQuery) {
                $clientQuery->where('status', $this->status);
            });
        }

        if ($this->report_status !== 'all') {
            $query->whereHas('client', function ($clientQuery) {
                $clientQuery->where('status', $this->report_status);
            });
        }

        if ($this->payment_type !== 'all' && !$advancedFiltersActive) {
            $query->where(
                'payment_type',
                'ILIKE',
                '%' . $this->payment_type . '%'
            );
        }

        if ($this->report_payment_type !== 'all') {
            $query->where(
                'payment_type',
                'ILIKE',
                '%' . $this->report_payment_type . '%'
            );
        }

        return view('livewire.invoices.table', [
            'invoices' => $query
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->orderBy('invoice_date', 'desc')
                ->paginate(10),
        ]);
    }
}
