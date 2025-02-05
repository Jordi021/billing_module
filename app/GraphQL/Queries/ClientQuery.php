<?php declare(strict_types=1);

namespace App\GraphQL\Queries;
use App\Models\Client;

final readonly class ClientQuery {
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args) {
        // TODO implement the resolver
    }

    public function clientsWithCreditInvoices($root, array $args) {
        return Client::with(['invoices' => function ($query) {
            $query->where('payment_type', 'Credit')
                  ->orderBy('created_at', 'desc');
        }])
        ->whereHas('invoices', function ($query) {
            $query->where('payment_type', 'Credit');
        })
        ->get();
    }

    public function clientWithCreditInvoices($root, array $args) {
        $clientId = $args['id'];

        return Client::where('id', $clientId)
            ->whereHas('invoices', function ($query) {
                $query->where('payment_type', 'Credit');
            })
            ->with([
                'invoices' => function ($query) {
                    $query->where('payment_type', 'Credit')->with('details');
                },
            ])
            ->first();
    }

    public function clientsWithAllInvoices($root, array $args) {
        return Client::with(['invoices' => function ($query) {
            $query->orderBy('created_at', 'desc')->with('details');
        }])
        ->whereHas('invoices')
        ->get();
    }

    public function clientWithAllInvoices($root, array $args) {
        $clientId = $args['id'];
        
        return Client::where('id', $clientId)
            ->with(['invoices' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('details');
            }])
            ->first();
    }
}
