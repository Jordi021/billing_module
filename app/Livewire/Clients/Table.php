<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Table extends Component {
    use WithPagination;

    public $search = "";
    public $status = "all";
    public $client_type = "all";
    protected $queryString = [
        "search" => ["except" => "", "as" => "s"],
        "status" => ["except" => "all", "as" => "st"],
        "client_type" => ["except" => "all", "as" => "ct"],
    ];
    protected $paginationTheme = "tailwind";

    public function updated($propertyName) {
        if (in_array($propertyName, ["search", "status", "client_type"])) {
            $this->resetPage();
        }
    }

    #[On("client-created/updated")]
    public function refresh() {
        $this->resetPage();
    }

    #[On('filter-updated')]
    public function applyFilter($filters)
    {
        $this->search = $filters['search'];
        $this->status = $filters['status'];
        $this->client_type = $filters['client_type'];
        $this->resetPage();
    }

    public function render() {
        $query = Client::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where("name", "ILIKE", "%" . $this->search . "%")
                    ->orWhere("id", "ILIKE", "%" . $this->search . "%")
                    ->orWhere("email", "ILIKE", "%" . $this->search . "%")
                    ->orWhere("last_name", "ILIKE", "%" . $this->search . "%");
            });
        }

        if ($this->status !== "all") {
            $query->where("status", $this->status);
        }

        if ($this->client_type !== "all") {
            $query->where("client_type", $this->client_type);
        }

        return view("livewire.clients.table", [
            "clients" => $query->orderBy("updated_at", "desc")->paginate(10),
        ]);
    }
}
