<div x-data="{ 
    lockedInvoices: {},
    lockInvoice(id) {
        this.lockedInvoices[id] = true;
    }
}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-b-lg">
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
                <!-- Vista móvil, tablet y laptop (tarjetas) -->
                <div class="w-full xl:hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($invoices as $invoice)
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 relative min-h-[280px]">
                            <!-- Contenedor para todo el contenido excepto los botones -->
                            <div class="space-y-3 pb-14">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold">#{{ $invoice['id'] }}</span>
                                    <div>{{ $invoice['invoice_date'] }}</div>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Cliente -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Client') }}</p>
                                            <div class="flex items-center gap-2">
                                                @php
                                                    $color = $invoice->client->status
                                                        ? 'bg-green-400 hover:bg-green-500'
                                                        : 'bg-gray-400 hover:bg-gray-500';
                                                    $imgSrc = $invoice->client->status
                                                        ? 'svg/active.svg'
                                                        : 'svg/inactive.svg';
                                                @endphp
                                                <x-custom-button color="{{ $color }}" img="{{ $imgSrc }}"
                                                    wire:click="$dispatch('client-modal', { clientId: '{{ $invoice->client_id }}' })" />
                                                <span>{{ $invoice->client->name[0] }}.
                                                    {{ $invoice->client->last_name }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Details') }}</p>
                                            <x-custom-button color="bg-blue-400" icon="fas fa-book"
                                                wire:click="$dispatch('details-modal', { invoiceId: '{{ $invoice->id }}' })" />
                                        </div>

                                    </div>

                                    <!-- Forma de pago y detalles -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('Payment Type') }}</p>
                                            <p>{{ $invoice['payment_type'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                                            <p>{{ $invoice['total'] ? '$' . $invoice['total'] : '$0' }}</p>
                                        </div>
                                    </div>

                                    <!-- Nota (si existe) -->
                                    @if ($invoice['note'])
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Note') }}</p>
                                            <p>{{ $invoice['note'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="absolute bottom-4 right-4 flex items-center gap-3">
                                <a href="{{ URL('invoices/pdf/' . $invoice->id) }}"
                                    @click="lockInvoice({{ $invoice->id }})"
                                    class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-md text-sm font-semibold hover:bg-blue-600 focus:ring focus:ring-blue-300 transition"
                                    id="download-pdf-button-{{ $invoice->id }}">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <div class="flex gap-1">
                                    <x-custom-button :disabled="session('locked_invoice_' . $invoice->id)"
                                        x-bind:disabled="lockedInvoices[{{ $invoice->id }}]"
                                        color="{{ session('locked_invoice_' . $invoice->id) ? 'bg-gray-400' : 'bg-indigo-400' }}"
                                        icon="fa fa-pencil" id="edit-button-{{ $invoice->id }}"
                                        wire:click="$dispatch('invoice-edit', { invoice: {{ $invoice }}, details: {{ $invoice->details }} })" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Vista desktop XL (tabla) -->
                <div class="hidden xl:block w-full">
                    <table class="min-w-full divide-y divide-gray-200 table-auto text-base">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                @foreach (['Invoice_ID', 'Client', 'Payment Type', 'Date', 'Note', 'Details', 'Total', 'Actions'] as $header)
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
                                        <div class="flex items-center gap-3">
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

                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $invoice['total'] ? '$' . $invoice['total'] : '$0' }}</td>

                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex gap-3">

                                            <a href="{{ URL('invoices/pdf/' . $invoice->id) }}"
                                                @click="lockInvoice({{ $invoice->id }})"
                                                class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-md text-sm font-semibold hover:bg-blue-600 focus:ring focus:ring-blue-300 transition"
                                                id="download-pdf-button-{{ $invoice->id }}">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <div class="flex gap-1">
                                                <x-custom-button :disabled="session('locked_invoice_' . $invoice->id)"
                                                    x-bind:disabled="lockedInvoices[{{ $invoice->id }}]"
                                                    color="{{ session('locked_invoice_' . $invoice->id) ? 'bg-gray-400' : 'bg-indigo-400' }}"
                                                    icon="fa fa-pencil" id="edit-button-{{ $invoice->id }}"
                                                    wire:click="$dispatch('invoice-edit', { invoice: {{ $invoice }}, details: {{ $invoice->details }} })" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 w-full">
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