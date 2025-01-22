<div>
    <form wire:submit="save" class="p-6" id="form-invoice">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ $isEditing ? __('Edit invoice') : __('Create New invoice') }}
        </h2>

        <div class="mb-4" wire:ignore>
            <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Client') }}
            </label>
            <select id="select-client" name="client_id" autocomplete="off" wire:form.client_id>
            </select>
            <div id="client-error" class="mt-2"></div>
        </div>

        <div class="mb-4">
            <x-date-input label="{{ __('Date') }}" name="invoice_date" wire:model="form.invoice_date" />
            <x-input-error :messages="$errors->get('form.invoice_date')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-select-input label="{{ __('Payment Type') }}" name="payment_type" wire:model="form.payment_type">
                <option value="" disabled selected>{{ __('Select a type...') }}</option>
                <option value="cash">{{ __('Cash') }}</option>
                <option value="credit">{{ __('Credit') }}</option>
            </x-select-input>
            <x-input-error :messages="$errors->get('form.payment_type')" class="mt-2" />
        </div>

        <div class="mb-4">
            <p class="block  font-bold text-gray-700 dark:text-gray-300">
                {{ __('Details') }}
            </p>
            <x-input-error :messages="$errors->get('form.details')" class="mt-2" />
            <x-input-error :messages="$errors->get('form.total')" class="mt-2" />

            <label for="product" class="mt-4 block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Product') }}
            </label>

            <div class="space-y-4">
                <div class="flex items-center w-full gap-4">
                    <div class="flex-grow" wire:ignore>
                        <select id="select-product" name="product" multiple autocomplete="off"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                        </select>
                    </div>
                    <div class="w-24">
                        <input type="number" id="qty-input" min="1" placeholder="Qty"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                    </div>
                    <div class="flex-shrink-0">
                        <button id="add-btn" type="button"
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
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ID
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Product
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Price
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Subtotal
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="details-table"
                        class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @if (!empty($form->details) && is_array($form->details))
                            @foreach ($form->details as $index => $detail)
                                <tr wire:key="detail-{{ $detail['product_id'] }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $detail['product_id'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $detail['product_name'] }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                        ${{ number_format($detail['unit_price'], 2) }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                        {{ $detail['quantity'] }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                        ${{ number_format($detail['subtotal'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button" wire:click="removeDetail({{ $index }})"
                                            class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No details available.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>



                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td colspan="4"
                                class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">Total:
                            </td>
                            <td id="total-invoice"
                                class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                                @if (!empty($form->total))
                                    ${{ number_format($form->total, 2) }}
                                @endif
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mb-4">
            <x-text-float-input label="{{ __('Note') }}" name="note" wire:model="form.note" />
            <x-input-error :messages="$errors->get('form.note')" class="mt-2" />
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
    document.addEventListener('DOMContentLoaded', function() {
        initializeTomSelect();
        //console.log("DOM fully loaded and parsed");

        // Attach the event listener to the Add button after DOM is ready
        setupAddButton();
    });

    document.addEventListener('livewire:init', function() {
        initializeTomSelect();
        //console.log("Livewire init event triggered");

        // Re-attach the event listener to the Add button after Livewire components load
        setupAddButton();

        const selectClient = document.querySelector('#select-client');
        Livewire.on('validate-client-id', (message) => {

            if (selectClient) {
                const tomSelectClient = selectClient.tomselect;

                const errorContainer = document.querySelector('#client-error');
                if (errorContainer) {
                    errorContainer.innerHTML =
                        `<ul class="text-sm text-red-600 space-y-1"><li>${message}</li></ul>`;
                }
            }
        });

        Livewire.on('clear-validate-client-id', () => {
            const selectClient = document.querySelector('#select-client');

            if (selectClient) {
                const tomSelectClient = selectClient.tomselect;

                const errorContainer = document.querySelector('#client-error');
                if (errorContainer) {
                    errorContainer.innerHTML = '';
                }
            }
        });

        Livewire.hook('morph.updated', ({
            el
        }) => {
            initializeTomSelect();
            setupAddButton();
        });

    });

    function initializeTomSelect() {
        const selectClient = document.querySelector('#select-client');
        const selectProduct = document.querySelector('#select-product');

        // Initialize TomSelect for Clients
        if (selectClient && !selectClient.tomselect) {
            console.log("Initializing TomSelect for Clients");
            const tomSelectClient = new TomSelect(selectClient, {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                options: @json($clients),
                persist: false,
                create: false,
                maxItems: 1,
                onChange: function(value) {
                    @this.set('form.client_id', value);
                    console.log("Client changed:", @this.get('form.client_id'));
                },
            });

            Livewire.on('fill-client-select', clientValue => {
                tomSelectClient.setValue(clientValue);
            });

            Livewire.on('reset-client-select', () => {
                tomSelectClient.clear();
            });
        }


        // Initialize TomSelect for Products
        if (selectProduct && !selectProduct.tomselect) {
            console.log("Initializing TomSelect for Products");
            new TomSelect(selectProduct, {
                valueField: 'id',
                labelField: 'title',
                searchField: 'title',
                options: @json($products),
                persist: false,
                create: false,
                maxItems: 1,
                render: {
                    option: function(data, escape) {
                        return `<div class="flex justify-between items-center py-2">
                                <span class="text-sm">${escape(data.title)}</span>
                                <span class="text-sm font-semibold">$${escape(data.price)}</span>
                            </div>`;
                    },
                    item: function(data, escape) {
                        return `<div>
                                <span class="text-sm">${escape(data.title)} - </span>
                                <span class="text-sm font-semibold">$${escape(data.price)}</span>
                            </div>`;
                    },
                },
                onChange: function(value) {
                    console.log("Product changed:", value);
                    @this.set('selectedProduct', value);
                },
            });
        }
    }

    // Function to set up the Add button event listener
    function setupAddButton() {
        const addButton = document.getElementById('add-btn');
        if (addButton) {
            addButton.removeEventListener('click', handleAddButtonClick); // Avoid duplicate listeners
            addButton.addEventListener('click', handleAddButtonClick);
        }
    }

    // Function to handle Add button click
    function handleAddButtonClick() {
        const selectProduct = document.querySelector('#select-product');
        const qtyInput = document.getElementById('qty-input');

        if (!selectProduct || !qtyInput || !selectProduct.tomselect) {
            alert('Please ensure the product selector and quantity input are correctly initialized.');
            return;
        }

        const productInstance = selectProduct.tomselect;
        const selectedProductIdArray = productInstance.getValue(); // Get the array of selected product IDs
        const selectedProductId = Array.isArray(selectedProductIdArray) && selectedProductIdArray.length > 0 ?
            selectedProductIdArray[0] :
            null; // Extract the first ID if available

        const selectedProductData = selectedProductId ? productInstance.options[selectedProductId] : null;


        // Debugging logs
        console.log('Selected Product ID:', selectedProductId);
        console.log('Selected Product Data:', selectedProductData);
        console.log('Quantity:', qtyInput.value);

        if (!selectedProductData || !qtyInput.value || qtyInput.value <= 0) {
            alert('Please select a valid product and enter a valid quantity.');
            return;
        }

        const quantity = parseInt(qtyInput.value);

        // Call Livewire method
        @this.call('addDetail', selectedProductId, quantity);

        // Clear inputs
        qtyInput.value = '';
        productInstance.clear();
    }
</script>
