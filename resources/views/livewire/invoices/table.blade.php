<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-b-lg">
    <div class="overflow-hidden">
        <div class="p-4 text-gray-900 dark:text-gray-100 overflow-x-auto flex flex-col items-center">
            @if ($invoices->isNotEmpty())
                <table class="min-w-full divide-y divide-gray-200 table-auto text-base">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @foreach (['ID', 'Client', 'Payment Type', 'Date', 'Total', 'Note', 'Details', 'Actions'] as $header)
                                <th scope="col"
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
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

                                    <x-custom-button color="{{ $color }}" img="{{ $imgSrc }}"
                                        wire:click="$dispatch('client-modal', { clientId: '{{ $invoice->client_id }}' })" />
                                </td>

                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['payment_type'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['invoice_date'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['total'] ?? '$0' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $invoice['note'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <x-custom-button color="bg-blue-400" icon="fas fa-book"
                                        wire:click="$dispatch('details-modal', { invoiceId: '{{ $invoice->id }}' })" />
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <x-custom-button color="bg-indigo-400" icon="fa fa-pencil"
                                            wire:click="$dispatch('invoice-edit', { invoice: {{ $invoice }} })" />

                                        <x-custom-button color="bg-red-400" icon="fas fa-trash"
                                            wire:click="$dispatch('invoice-confirm', {invoice: {{ $invoice }} })" />

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
                    {{ __('No invocies available.') }}
                </p>
            @endif
        </div>
    </div>
</div>
