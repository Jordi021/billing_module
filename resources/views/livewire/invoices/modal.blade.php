<div>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ $isEditing ? __('Edit invoice') : __('Create New invoice') }}
        </h2>

        <div class="mb-4" wire:ignore>
            <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{__('Client')}}
            </label>
            <select id="select-client" name="client_id" multiple autocomplete="off">
            </select>
        </div>

        <div class="mb-4">
            <x-date-input
                label="{{ __('Date') }}"
                name="invoice_date"
                wire:model="form.invoice_date"
            />
            <x-input-error :messages="$errors->get('form.invoice_date')" class="mt-2"/>
        </div>
        <div class="mb-4">
            <x-select-input
                label="{{ __('Payment Type') }}"
                name="payment_type"
                wire:model.live="form.payment_type">
                <option value="" disabled selected>{{ __('Select a type...') }}</option>
                <option value="cash">{{ __('Cash') }}</option>
                <option value="credit">{{ __('Credit') }}</option>
            </x-select-input>
            <x-input-error :messages="$errors->get('form.payment_type')" class="mt-2"/>
        </div>

        <div class="mb-4" wire:ignore>
            <p class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                {{__('Invoice Details')}}
            </p>

            <label for="product" class="mt-4 block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{__('Product')}}
            </label>

            <form wire:submit.prevent="addDetail" class="space-y-4">
                <div class="flex items-center w-full gap-4">
                    <div class="flex-grow">
                        <select id="select-product" name="product" multiple autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                        </select>
                    </div>
                    <div class="w-24">
                        <input type="number"
                               wire:model="quantity"
                               min="1"
                               placeholder="Qty"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600"/>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Details Table -->
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Subtotal
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @foreach($invoiceDetails as $index => $detail)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ $detail['title'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                    ${{ number_format($detail['price'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                    @if($editingIndex === $index)
                                        <input type="number"
                                               wire:model="editingQuantity"
                                               class="w-20 px-2 py-1 text-right border rounded"
                                               min="1"
                                               @blur="$wire.updateQuantity({{ $index }})"/>
                                    @else
                                        <span wire:click="editRow({{ $index }})" class="cursor-pointer">
                                    {{ $detail['quantity'] }}
                                </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                    ${{ number_format($detail['quantity'] * $detail['price'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="removeDetail({{ $index }})"
                                            class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td colspan="3"
                                class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">Total:
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                                ${{ number_format($total, 2) }}
                            </td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <x-text-float-input
                label="{{ __('Note') }}"
                name="note"
                wire:model="form.note"
            />
            <x-input-error :messages="$errors->get('form.note')" class="mt-2"/>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button wire:click.prevent="closeModal">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ $isEditing ? __('Update') : __('Create') }}
            </x-primary-button>
        </div>
    </form>
</div>


<script>
    document.addEventListener('livewire:navigated', function () {
        const selectClient = document.querySelector('#select-client');
        const selectProduct = document.querySelector('#select-product');

        if (selectClient) {
            const clients = @json($clients);

            new TomSelect(selectClient, {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                options: clients,
                persist: false,
                create: false,
                maxItems: 1,
                onInitialize: function () {
                    @if($selectedClient)
                        this.setValue('{{ $selectedClient }}');
                    @endif
                },
                onChange: function (value) {
                    @this.set('selectedClient', value.toString());
                    @this.set('form.client_id', value.toString());
                },
            });
        }

        if (selectProduct) {
            const products = @json($products);

            let instance = new TomSelect(selectProduct, {
                valueField: 'id',
                labelField: 'title',
                searchField: 'title',
                options: products,
                persist: false,
                create: false,
                maxItems: 1,
                render: {
                    option: function (data, escape) {
                        return `<div class="flex justify-between items-center py-2">
                        <span class="text-sm">${escape(data.title)}</span>
                        <span class="text-sm font-semibold">$${escape(data.price)}</span>
                    </div>`;
                    },
                    item: function (data, escape) {
                        return `<div>
                        <span class="text-sm">${escape(data.title)} - </span>
                        <span class="text-sm font-semibold">$${escape(data.price)}</span>
                    </div>`;
                    }
                },
                onInitialize: function () {
                    @if($selectedProduct)
                        this.setValue('{{ $selectedProduct }}');
                    @endif
                },
                onChange: function (value) {
                    @this.set('selectedProduct', value.toString());
                },
            });

            
        }

        Livewire.on('resetProduct', () => {
            if (selectProduct && selectProduct.tomselect) {
                selectProduct.tomselect.clear();
            }
        });
    });

</script>
