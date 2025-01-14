<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Table extends Component
{
    use WithPagination;

    public $search = "";
    public $status = "all";
    public $payment_type = "all";
    protected $queryString = [
        "search" => ["except" => "", "as" => "s"],
        "status" => ["except" => "all", "as" => "st"],
        "payment_type" => ["except" => "all", "as" => "ct"],
    ];
    protected $paginationTheme = "tailwind";

    public function updated($propertyName)
    {
        if (in_array($propertyName, ["search", "status", "payment_type"])) {
            $this->resetPage();
        }
    }

    #[On("invoice-created/updated")]
    public function refresh()
    {
        $this->resetPage();
    }

    #[On('filter-updated')]
    public function applyFilter($filters)
    {
        $this->search = $filters['search'];
        $this->status = $filters['status'];
        $this->payment_type = $filters['payment_type'];
        $this->resetPage();
    }

    public function render()
    {
        $query = Invoice::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where("payment_type", "ILIKE", "%" . $this->search . "%")
                    ->orWhere("invoice_date", "ILIKE", "%" . $this->search . "%")
                    ->orWhere("total", "ILIKE", "%" . $this->search . "%")
                    ->orWhere("note", "ILIKE", "%" . $this->search . "%")
                    ->orWhereHas('client', function ($clientQuery) {
                        $clientQuery->where('name', "ILIKE", "%" . $this->search . "%")
                            ->orWhere('last_name', "ILIKE", "%" . $this->search . "%")
                            ->orWhere('id', "ILIKE", "%" . $this->search . "%")
                            ->orWhere('address', "ILIKE", "%" . $this->search . "%")
                            ->orWhere('email', "ILIKE", "%" . $this->search . "%");
                    })
                    ->orWhereHas('details', function ($detailQuery) {
                        $detailQuery->where('product_id', "ILIKE", "%" . $this->search . "%")
                            ->orWhere('product_name', "ILIKE", "%" . $this->search . "%");
                    });
            });
        }

        if ($this->status !== "all") {
            logger('Client status in invoice:', ['status invoice' => $this->status]);
            $query->whereHas('client', function ($clientQuery) {
                $clientQuery->where("status",  $this->status);
            });
        }

        if ($this->payment_type !== "all") {
            $query->where("payment_type", "ILIKE", "%" . $this->payment_type . "%");
        }

        return view("livewire.invoices.table", [
            "invoices" => $query
                ->orderBy("updated_at", "desc")
                ->paginate(10),
        ]);
    }
}
