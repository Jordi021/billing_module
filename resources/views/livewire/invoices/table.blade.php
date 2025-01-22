<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-b-lg">
    <style>
        button:disabled {
            background-color: #d1d5db;
            color: #6b7280;
            opacity: 0.75;
            cursor: not-allowed;
            border-color: #d1d5db;
        }
    </style>
    <div class="overflow-hidden">
        <div class="p-4 text-gray-900 dark:text-gray-100 overflow-x-auto flex flex-col items-center">
            @if ($invoices->isNotEmpty())
                <table class="min-w-full divide-y divide-gray-200 table-auto text-base">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @foreach (['ID', 'Client', 'Payment Type', 'Date', 'Note', 'Details', 'Total', 'Actions'] as $header)
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __($header) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                        @foreach ($invoices as $invoice)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['id'] }}</td>

                                <td class="px-4 py-2 whitespace-nowrap">
                                    @php
                                        $color = '';
                                        $imgSrc = '';
                                        if ($invoice->client->status) {
                                            $color = 'bg-green-400 hover:bg-green-500';
                                            $imgSrc = 'svg/active.svg';
                                        } else {
                                            $color = 'bg-gray-400 hover:bg-gray-500';
                                            $imgSrc = 'svg/inactive.svg';
                                        }
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <x-custom-button color="{{ $color }}" img="{{ $imgSrc }}"
                                            wire:click="$dispatch('client-modal', { clientId: '{{ $invoice->client_id }}' })" />
                                        <div>
                                            {{ $invoice->client->name[0] }}. {{ $invoice->client->last_name }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['payment_type'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['invoice_date'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['note'] }}</td>

                                <td class="px-4 py-2 whitespace-nowrap">
                                    <x-custom-button color="bg-blue-400" icon="fas fa-book"
                                        wire:click="$dispatch('details-modal', { invoiceId: '{{ $invoice->id }}' })" />
                                </td>

                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['total'] ? '$'.$invoice['total'] : '$0' }}</td>

                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <a href="{{ URL('invoices/pdf/' . $invoice->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-md text-sm font-semibold hover:bg-blue-600 focus:ring focus:ring-blue-300 transition"
                                           id="download-pdf-button-{{ $invoice->id }}">
                                           <i class="fas fa-file-pdf"></i>
                                        </a>

                                        <x-custom-button 
                                            :disabled="session('locked_invoice_' . $invoice->id)" 
                                            color="{{ session('locked_invoice_' . $invoice->id) ? 'bg-gray-400' : 'bg-indigo-400' }}" 
                                            icon="fa fa-pencil"
                                            id="edit-button-{{ $invoice->id }}" 
                                            wire:click="$dispatch('invoice-edit', { invoice: {{ $invoice }}, details: {{$invoice->details}} })" 
                                        />

                                        <x-custom-button 
                                            :disabled="session('locked_invoice_' . $invoice->id)" 
                                            color="{{ session('locked_invoice_' . $invoice->id) ? 'bg-gray-400' : 'bg-red-400' }}" 
                                            icon="fas fa-trash"
                                            id="delete-button-{{ $invoice->id }}" 
                                            wire:click="$dispatch('invoice-confirm', {invoice: {{ $invoice }} })" 
                                        />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            @else
                <p class="text-center text-gray-500 dark:text-gray-300">
                    {{ __('No invoices available.') }}
                </p>
            @endif
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[id^="download-pdf-button-"]').forEach(function(button) {
        button.addEventListener('click', function() {
            const invoiceId = this.id.split('-').pop(); 

            const editButton = document.getElementById('edit-button-' + invoiceId);
            const deleteButton = document.getElementById('delete-button-' + invoiceId);

            [editButton, deleteButton].forEach(btn => {
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        });
    });
</script>
