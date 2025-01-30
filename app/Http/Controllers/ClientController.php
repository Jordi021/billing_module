<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
//use OwenIt\Auditing\Models\Audit;

class ClientController extends Controller {
    /**
     * Display a list of clients.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        return view('clients.index');
    }

    /**
     * Store a newly created client in the database.
     *
     * This method validates the incoming request using ClientRequest,
     * creates a new client with a default 'active' status, and redirects
     * back to the client list with a success message.
     *
     * @param  \App\Http\Requests\ClientRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ClientRequest $request) {
        // Validate and retrieve validated data
        $data = $request->validated();

        $data['status'] = true;
        Client::create($data);
        return redirect()->back()->with('success', 'Cliente creado.');
    }

    /**
     * Update an existing client in the database.
     *
     * This method validates the request data using ClientRequest,
     * finds the client by ID using route model binding, updates its
     * attributes, and redirects back to the client list with a success message.
     *
     * @param  \App\Http\Requests\ClientRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ClientRequest $request, Client $client) {
        $data = $request->validated();
        $client->update($data);

        return redirect()->back()->with('success', 'Cliente actualizado.');
    }

    /**
     * Deactivate (soft delete) a client.
     *
     * This method finds a client by its ID, ensures it exists, and sets its
     * status to 'inactivo' (inactive). This is a soft delete as the record
     * remains in the database but is considered deactivated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Client $client) {
        $client->update(['status' => false]);
        return redirect()->back()->with('success', 'Client eliminado.');
    }

    /**
     * Restore an inactive client.
     *
     * This method finds a client by its ID and ensures it is inactive.
     * It then sets the client's status to 'active' and redirects back
     * to the client list with a success message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Client $client) {
        $client->update(['status' => true]);

        return redirect()->back()->with('success', 'Cliente restaurado.');
    }

    /**
     * GenerarPDF
     *
     * This function retrieves all clients from the database,
     * generates a PDF document using the "clients.pdf" view,
     * and returns the PDF file for download as "report_clients.pdf".
     *
     * @return \Illuminate\Http\Response
     */

    public function GenerarPDF() {
        $clients = Client::all();
        $pdf = PDF::loadView('livewire.clients.pdf', compact('clients'));
        return $pdf->download('report_clients.pdf');
    }
}
