<div>
    <form wire:submit="save" class="p-6" id="form-invoice">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ $isEditing ? __('Edit invoice') : __('Create New invoice') }}
        </h2>

        <div class="mb-4" wire:ignore>
            <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{__('Client')}}
            </label>
            <select id="select-client" name="client_id" multiple autocomplete="off" wire:form.client_id>
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
            <p class="block  font-bold text-gray-700 dark:text-gray-300">
                {{__('Details')}}
            </p>
            <x-input-error :messages="$errors->get('form.details')" class="mt-2"/>
            <x-input-error :messages="$errors->get('form.total')" class="mt-2"/>

            <label for="product" class="mt-4 block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{__('Product')}}
            </label>

            <div class="space-y-4">
                <div class="flex items-center w-full gap-4">
                    <div class="flex-grow">
                        <select id="select-product" name="product" multiple autocomplete="off"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                        </select>
                    </div>
                    <div class="w-24">
                        <input type="number"
                               id="qty-input"
                               min="1"
                               placeholder="Qty"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600"/>
                    </div>
                    <div class="flex-shrink-0">
                        <button
                            id="add-btn"
                            type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>


            <input type="hidden" id="details-input" name="details" wire:model="form.details">
            <input type="hidden" id="total-input" name="total" wire:model="form.total">

            <!-- Details Table -->
            <div class="overflow-y-auto max-h-72 mt-4">
                <table id="details-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            ID
                        </th>
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
                        </th>
                    </tr>
                    </thead>
                    <tbody id="details-table"
                           class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($details as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $detail['product_id'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $detail['product_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                ${{ number_format($detail['unit_price'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">{{ $detail['quantity'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                ${{ number_format($detail['subtotal'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button"
                                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td colspan="4"
                            class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">Total:
                        </td>
                        <td id="total-invoice"
                            class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                        </td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
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
        const tableBody = document.querySelector('#details-table tbody');
        const detailsInput = document.querySelector('#details-input');
        const totalInput = document.querySelector('#total-input');
        let productInstance = null;
        let total = 0;
        let details = [];

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
                    @this.
                    set('selectedClient', value.toString());
                    @this.
                    set('form.client_id', value.toString());
                },
            });
        }

        if (selectProduct) {
            const products = @json($products);

            productInstance = new TomSelect(selectProduct, {
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
                    @this.
                    set('selectedProduct', value.toString());
                },
            });


        }

        document.getElementById('add-btn').addEventListener('click', function () {
            if (!productInstance) {
                console.log('Product selector is not initialized.');
                return;
            }

            const selectedProductId = productInstance.getValue();
            const selectedProductData = productInstance.options[selectedProductId];

            let qtyInput = document.getElementById('qty-input');

            if (!qtyInput || !qtyInput.value || qtyInput.value <= 0) {
                alert('Please enter a valid quantity.');
                return;
            }

            const quantity = parseInt(qtyInput.value);
            const subtotal = (selectedProductData.price * quantity).toFixed(2);
            total += parseFloat(subtotal);

            const detailIndex = details.length;
            // Add to details array
            details.push({
                index: detailIndex,
                product_id: selectedProductData.id,
                product_name: selectedProductData.title,
                unit_price: selectedProductData.price,
                quantity: quantity,
                subtotal: parseFloat(subtotal)
            });

            @this.
            set('form.details', details);
            @this.
            set('form.total', total);

            console.log('Details input');
            console.log(details);

            // Update hidden inputs
            detailsInput.value = JSON.stringify(details);
            totalInput.value = total.toFixed(2);

            // Create a new row
            let row = document.createElement('tr');
            row.setAttribute('data-index', detailIndex);

            // Add cells to the row
            row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">${selectedProductData.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">${selectedProductData.title}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">$${selectedProductData.price.toFixed(2)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">${qtyInput.value}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">$${subtotal}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type='button' class="delete-row text-red-600 hover:text-red-900 dark:hover:text-red-400">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;


            // Append the row to the tbody
            tableBody.appendChild(row);

            // Update total
            const totalInvoice = document.getElementById('total-invoice');
            totalInvoice.textContent = `$${total.toFixed(2)}`

            // Add event listener to the delete button
            row.querySelector('.delete-row').addEventListener('click', function () {

                const rowIndex = parseInt(row.getAttribute('data-index'));
                // Remove the corresponding entry from the details array
                details = details.filter(detail => detail.index !== rowIndex);

                console.log('Details updated');
                console.log(details);

                @this.
                set('form.details', details);
                @this.
                set('form.total', total);

                // Recalculate total when deleting a row
                const rowSubtotal = parseFloat(subtotal);
                total -= rowSubtotal;
                totalInvoice.textContent = `$${total.toFixed(2)}`;
                row.remove(); // Remove the row from the DOM
            });

            // Clear inputs
            qtyInput.value = '';
            productInstance.clear();
        });

        Livewire.on('resetProduct', () => {
            if (selectProduct && selectProduct.tomselect) {
                selectProduct.tomselect.clear();
            }
        });
    });

</script>
