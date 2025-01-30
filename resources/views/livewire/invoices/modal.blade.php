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
            <p class="block font-bold text-gray-700 dark:text-gray-300">
                {{ __('Details') }}
            </p>
            <x-input-error :messages="$errors->get('form.details')" class="mt-2" />
            <x-input-error :messages="$errors->get('form.total')" class="mt-2" />

            <!-- Rediseño del selector de productos -->
            <div class="mt-4 space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-9" wire:ignore>
                        <select id="select-product" name="product" multiple autocomplete="off" class="w-full">
                        </select>
                    </div>
                    <div class="md:col-span-3 flex items-center space-x-2" wire:ignore>
                        <div class="flex items-center border dark:border-gray-600 rounded-lg w-auto">
                            <button type="button" onclick="decrementQuantity()" id="btn-decrease"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-l-lg disabled:opacity-40 disabled:cursor-not-allowed dark:text-white"
                                disabled>
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="text" id="qty-input" disabled
                                class="w-12 text-center border-none focus:ring-0 dark:bg-gray-800 dark:text-white" />
                            <button type="button" onclick="incrementQuantity()" id="btn-increase"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-r-lg disabled:opacity-40 disabled:cursor-not-allowed dark:text-white"
                                disabled>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button id="add-btn" type="button"
                            class="w-10 h-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center justify-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla responsiva -->
            <div class="mt-4">
                <div>
                    <table class="table-auto w-full whitespace-normal break-words">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 py-3 w-10"></th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase max-w-[200px] lg:max-w-[300px]">
                                    {{ __('Product') }}
                                </th>
                                <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-20 sm:w-24 sm:px-4">
                                    {{ __('Price') }}
                                </th>
                                <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-24 sm:w-32 sm:px-4">
                                    {{ __('Quantity') }}
                                </th>
                                <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-20 sm:w-24 sm:px-4">
                                    {{ __('Subtotal') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @if (!empty($form->details) && is_array($form->details))
                                @foreach ($form->details as $index => $detail)
                                    <tr wire:key="detail-{{ $detail['product_id'] }}">
                                        <td class="px-2 py-3 text-left sm:px-4">
                                            <button type="button" wire:click="removeDetail({{ $index }})"
                                                class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                        <td class="px-2 py-3 text-sm text-gray-900 dark:text-gray-200 sm:px-4">
                                            <div class="truncate w-[100px] sm:w-[180px] md:w-[220px] lg:w-[280px] xl:w-[320px]"
                                                title="{{ $detail['product_name'] }}">
                                                {{ $detail['product_name'] }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-3 text-sm text-right text-gray-900 dark:text-gray-200 sm:px-4">
                                            ${{ number_format($detail['unit_price'], 2) }}
                                            <span class="text-xs text-gray-500 block sm:inline-block">+{{ $detail['vat_percentage'] }}%</span>
                                        </td>
                                        <td class="px-2 py-3 sm:px-4">
                                            <div class="flex items-center justify-center space-x-1">
                                                <button type="button"
                                                    wire:click="updateQuantity({{ $index }}, {{ $detail['quantity'] - 1 }})"
                                                    class="p-1 text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 dark:text-gray-200 {{ $detail['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $detail['quantity'] <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span class="text-sm text-gray-900 dark:text-gray-200 w-8 text-center">
                                                    {{ $detail['quantity'] }}
                                                </span>
                                                <button type="button"
                                                    wire:click="updateQuantity({{ $index }}, {{ $detail['quantity'] + 1 }})"
                                                    class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-200 dark:hover:text-gray-400">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-2 py-3 text-sm text-right text-gray-900 dark:text-gray-200 sm:px-4">
                                            ${{ number_format($detail['subtotal'] + $detail['vat_amount'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-2 py-3 text-center text-gray-500 dark:text-gray-400 sm:px-4">
                                        {{ __('No details available.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="4" class="px-2 py-3 text-right text-sm font-medium text-gray-900 dark:text-gray-200 sm:px-4">
                                    Total:
                                </td>
                                <td class="px-2 py-3 text-right text-sm font-medium text-gray-900 dark:text-gray-200 sm:px-4">
                                    ${{ number_format($form->total ?? 0, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
        // Evita llamar initializeTomSelect() aquí, para no sobrescribir tu configuración

        // Quita esta línea o coméntala si repetía la inicialización
        // initializeTomSelect();

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
                searchField: ['title', 'code', 'description'], // Agregamos description para búsqueda
                options: @json($products),
                persist: false,
                create: false,
                maxItems: 1,
                render: {
                    option: function(data, escape) {
                        return `<div class="flex flex-col py-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium dark:text-gray-200">${escape(data.title)}</span>
                                <div class="text-right">
                                    <span class="text-xs text-green-600 dark:text-green-400">$${escape(data.price)}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">+${escape(data.vat_percentage)}%</span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                ${escape(data.description)}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Stock: ${escape(data.stock)}
                            </div>
                        </div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="flex justify-between items-center">
                            <span class="text-sm font-medium dark:text-gray-200">${escape(data.title)}</span>
                        </div>`;
                    }
                },
                shouldLoad: function() {
                    return true; // Always load options
                },
                load: function(query, callback) {
                    callback(this.options); // Load all options
                },
                score: function(search) {
                    return this.getScoreFunction(search); // Removida toda la lógica de filtrado
                },
                onChange: function(value) {
                    console.log("Product changed:", value);
                    @this.set('selectedProduct', value);

                    const qtyInput = document.getElementById('qty-input');
                    const btnDecrease = document.getElementById('btn-decrease');
                    const btnIncrease = document.getElementById('btn-increase');

                    if (qtyInput && btnDecrease && btnIncrease) {
                        if (value) {
                            qtyInput.disabled = false;
                            qtyInput.value = '1';
                            btnDecrease.disabled = true;
                            btnIncrease.disabled = false;
                        } else {
                            qtyInput.disabled = true;
                            qtyInput.value = '';
                            btnDecrease.disabled = true;
                            btnIncrease.disabled = true;
                        }
                    }
                },
                onClear: function() {
                    console.log("Product cleared");
                    @this.set('selectedProduct', null);

                    const qtyInput = document.getElementById('qty-input');
                    const btnDecrease = document.getElementById('btn-decrease');
                    const btnIncrease = document.getElementById('btn-increase');

                    if (qtyInput && btnDecrease && btnIncrease) {
                        qtyInput.disabled = true;
                        qtyInput.value = '';
                        btnDecrease.disabled = true;
                        btnIncrease.disabled = true;
                    }
                }
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

        // Referenciamos btnDecrease y btnIncrease aquí
        const btnDecrease = document.getElementById('btn-decrease');
        const btnIncrease = document.getElementById('btn-increase');

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
        @this.call('addDetail', selectedProductId, quantity).then(() => {
            // Resetea todo
            productInstance.clear();
            qtyInput.value = '';
            qtyInput.disabled = true;
            btnDecrease.disabled = true;
            btnIncrease.disabled = true;
        });
    }

    function incrementQuantity() {
        const qtyInput = document.getElementById('qty-input');
        qtyInput.value = parseInt(qtyInput.value) + 1;
        handleQuantityChange(qtyInput);
    }

    function decrementQuantity() {
        const qtyInput = document.getElementById('qty-input');
        const newValue = parseInt(qtyInput.value) - 1;
        if (newValue >= 1) {
            qtyInput.value = newValue;
            handleQuantityChange(qtyInput);
        }
    }

    function handleQuantityChange(input) {
        const value = parseInt(input.value) || 1;
        const btnDecrease = document.getElementById('btn-decrease');
        const btnIncrease = document.getElementById('btn-increase');

        if (value < 1) input.value = 1;

        if (btnDecrease && btnIncrease) {
            btnDecrease.disabled = input.value <= 1; // sigue deshabilitado si es 1
            btnIncrease.disabled = false;
        }
    }
</script>
