<div x-data="{ isLoading: true }" x-init="$watch('$wire.isLoading', value => isLoading = value)">
    <div class="p-6">
        <!-- Título del modal, siempre visible -->
        <h2
            class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">
            {{ __('Invoice Details') }}
        </h2>
        @if ($isLoading)
            <!-- Skeleton para la tabla -->
            <div x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                @include('livewire.placeholders.details-modal-skeleton')
            </div>
        @else
            <!-- Contenido real, cuando ya se haya cargado -->
            <div x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                @if ($details)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Product') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Unit Price') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Quantity') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Subtotal') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('VAT') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('Total') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($details as $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/25">
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-300 font-medium">
                                                {{ $detail['product_name'] }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $detail['product_description'] }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-300">
                                            ${{ number_format($detail['unit_price'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-300">
                                            {{ $detail['quantity'] }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-300">
                                            ${{ number_format($detail['subtotal'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-sm text-gray-900 dark:text-gray-300">
                                                ${{ number_format($detail['vat_amount'], 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ({{ $detail['vat_percentage'] }}%)
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-300">
                                            ${{ number_format($detail['subtotal'] + $detail['vat_amount'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-200"></td>
                                    <td
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                                        ${{ number_format($details->sum('subtotal'), 2) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                                        ${{ number_format($details->sum('vat_amount'), 2) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                                        ${{ number_format($details->sum('subtotal') + $details->sum('vat_amount'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
            <div class="mt-8 flex justify-end">
                <x-secondary-button wire:click.prevent="closeModal">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        @endif
    </div>
</div>
