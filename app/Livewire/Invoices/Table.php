<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Table extends Component {
    use WithPagination;

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

    #[On('invoice-locked')]
    public function handleInvoiceLocked() {
        $this->refresh();
    }

    public function render() {
        $query = Invoice::query();

        if ($this->search) {
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

        if ($this->status !== 'all') {
            logger('Client status in invoice:', [
                'status invoice' => $this->status,
            ]);
            $query->whereHas('client', function ($clientQuery) {
                $clientQuery->where('status', $this->status);
            });
        }

        if ($this->payment_type !== 'all') {
            $query->where(
                'payment_type',
                'ILIKE',
                '%' . $this->payment_type . '%'
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
