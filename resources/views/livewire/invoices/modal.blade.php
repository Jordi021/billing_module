<div x-data="invoiceForm()">
    <form wire:submit="save" class="p-6" id="form-invoice">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ $isEditing ? __('Edit invoice') : __('Create New invoice') }}
        </h2>

        <div class="mb-4" wire:ignore>
            <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Client') }}
            </label>
            <select id="select-client" name="client_id" autocomplete="off" wire:model="form.client_id"
                x-init="initClientSelect($el)">
            </select>
            <div id="client-error" class="mt-2 text-sm text-red-600"></div>
        </div>

        <div class="mb-4">
            <x-date-input label="{{ __('Date') }}" name="invoice_date" type="datetime-local" readonly disabled
                :value="$form->invoice_date" class="bg-gray-100 dark:bg-gray-700 cursor-not-allowed" />
            <x-input-error :messages="$errors->get('form.invoice_date')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-select-input label="{{ __('Payment Type') }}" name="payment_type" wire:model="form.payment_type">
                <option value="" disabled selected>{{ __('Select a type...') }}</option>
                <option value="Cash">Cash</option>
                <option value="Credit">Credit</option>
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
                    <div class="md:col-span-9">
                        <!-- Añadir el toggle switch -->
                        <div class="flex items-center gap-2 mb-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="showOnlyInStock" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600">
                                </div>
                                <span
                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Show only products in stock') }}</span>
                            </label>
                        </div>
                        <div wire:ignore>
                            <select id="select-product" name="product" multiple autocomplete="off" class="w-full"
                                x-init="initProductSelect($el)">
                            </select>
                        </div>
                    </div>
                    <div class="md:col-span-3 flex items-end space-x-2">
                        <div class="flex items-center border dark:border-gray-600 rounded-lg w-auto">
                            <button type="button" @click="decrementQuantity()"
                                :disabled="quantity <= 1 || !selectedProduct"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-l-lg disabled:opacity-40 disabled:cursor-not-allowed dark:text-white">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" x-model.lazy="quantity" @input="validateQuantity"
                                @blur="restoreQuantityIfEmpty" @keydown.enter.prevent @keypress="preventNonNumeric"
                                @paste.prevent="handlePaste" @drop.prevent :maxlength="availableStock.toString().length"
                                @keydown="preventExcessLength" :disabled="!selectedProduct"
                                class="w-12 text-center border-none focus:ring-0 dark:bg-gray-800 dark:text-white disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-700"
                                min="0" step="1" inputmode="numeric" pattern="[0-9]*">
                            <button type="button" @click="incrementQuantity()"
                                :disabled="quantity >= availableStock || !selectedProduct"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-r-lg disabled:opacity-40 disabled:cursor-not-allowed dark:text-white">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button type="button" @click="addDetail()" :disabled="!canAdd"
                            class="w-10 h-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center justify-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla responsiva -->
            <div class="mt-4 overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <table class="table-auto w-full whitespace-normal break-words text-[15px] sm:text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-1 py-2 sm:px-2 sm:py-3 w-8 sm:w-10"></th>
                                <th
                                    class="px-1 py-2 sm:px-2 sm:py-3 text-left text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-[80px] sm:w-auto md:max-w-[200px] lg:max-w-[300px]">
                                    {{ __('Product') }}
                                </th>
                                <th
                                    class="px-1 py-2 sm:px-2 sm:py-3 text-right text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-16 sm:w-20 md:w-24">
                                    {{ __('Price') }}
                                </th>
                                <th
                                    class="px-1 py-2 sm:px-2 sm:py-3 text-center text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-16 sm:w-24 md:w-32">
                                    {{ __('Quantity') }}
                                </th>
                                <th
                                    class="px-1 py-2 sm:px-2 sm:py-3 text-right text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-16 sm:w-20 md:w-24">
                                    {{ __('Subtotal') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @if (!empty($form->details) && is_array($form->details))
                                @foreach ($form->details as $index => $detail)
                                    <tr x-data="{ 
                                        quantity: {{ $detail['quantity'] }},
                                        maxStock: {{ $detail['stock'] }},
                                        price: {{ $detail['unit_price'] }},
                                        vat: {{ $detail['vat_percentage'] }},
                                        updateTotal() {
                                            let subtotal = this.quantity * this.price;
                                            let vatAmount = subtotal * (this.vat / 100);
                                            @this.updateDetail({{ $index }}, this.quantity, subtotal, vatAmount);
                                        }
                                    }">
                                        <td class="px-1 py-2 sm:px-2 sm:py-3 text-left">
                                            <button type="button" @click="$wire.removeDetail({{ $index }})"
                                                class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                        <td
                                            class="px-1 py-2 sm:px-2 sm:py-3 text-[11px] sm:text-sm text-gray-900 dark:text-gray-200">
                                            <div class="truncate w-[80px] sm:w-[180px] md:w-[220px] lg:w-[280px] xl:w-[320px]"
                                                title="{{ $detail['product_name'] }}">
                                                {{ $detail['product_name'] }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-1 py-2 sm:px-2 sm:py-3 text-[11px] sm:text-sm text-right text-gray-900 dark:text-gray-200">
                                            <span
                                                class="hidden sm:inline">$</span>{{ number_format($detail['unit_price'], 2) }}
                                            <span
                                                class="hidden sm:inline text-[10px] sm:text-xs text-gray-500">+{{ $detail['vat_percentage'] }}%</span>
                                        </td>
                                        <td class="px-1 py-2 sm:px-2 sm:py-3">
                                            <div class="flex items-center justify-center space-x-0.5 sm:space-x-1">
                                                <button type="button"
                                                    wire:click.prevent="updateQuantity({{ $index }}, {{ $detail['quantity'] - 1 }})"
                                                    class="p-0.5 sm:p-1 text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 dark:text-gray-200 {{ $detail['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $detail['quantity'] <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span class="text-xs sm:text-sm text-gray-900 dark:text-gray-200 w-6 sm:w-8 text-center">
                                                    {{ $detail['quantity'] }}
                                                </span>
                                                <button type="button"
                                                    wire:click.prevent="updateQuantity({{ $index }}, {{ $detail['quantity'] + 1 }})"
                                                    class="p-0.5 sm:p-1 text-gray-500 hover:text-gray-700 dark:text-gray-200 dark:hover:text-gray-400 {{ $detail['quantity'] >= $detail['stock'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $detail['quantity'] >= $detail['stock'] ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td
                                            class="px-1 py-2 sm:px-2 sm:py-3 text-xs sm:text-sm text-right text-gray-900 dark:text-gray-200">
                                            <span
                                                class="hidden sm:inline">$</span>{{ number_format($detail['subtotal'] + $detail['vat_amount'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5"
                                        class="px-1 py-2 sm:px-2 sm:py-3 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('No details available.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="4"
                                    class="px-1 py-2 sm:px-2 sm:py-3 text-right text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Total:
                                </td>
                                <td
                                    class="px-1 py-2 sm:px-2 sm:py-3 text-xs sm:text-sm text-right text-gray-900 dark:text-gray-200">
                                    <span class="hidden sm:inline">$</span>{{ number_format($form->total ?? 0, 2) }}
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
            <x-secondary-button @click.prevent="$dispatch('close-modal', 'invoice-modal'); $wire.closeModal()">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3 flex items-center gap-2" 
                wire:loading.class="cursor-not-allowed">
                {{ $isEditing ? __('Update') : __('Create') }}
                <div wire:loading wire:target="save">
                    <x-loading-spinner color="black" />
                </div>
            </x-primary-button>
        </div>
    </form>
</div>

<script>
    function invoiceForm() {
        return {
            selectedProduct: null,
            quantity: 0,
            availableStock: 0,
            tomSelectProduct: null,
            tomSelectClient: null,
            showOnlyInStock: false, // Nueva variable para el toggle

            init() {
                this.$watch('selectedProduct', (value) => {
                    this.updateAvailableStock();
                    if (value) {
                        this.quantity = 1;
                    } else {
                        this.quantity = 0;
                    }
                });

                //  Livewire.on('stock-updated', () => {
                //      this.refreshStockAfterChange();
                //  });

                Livewire.on('close', () => {
                    this.resetForm();
                });

                Livewire.on('updatedORcreated', () => {
                    console.log('updatedORcreated');
                    
                });

                // Observar cambios en showOnlyInStock
                this.$watch('showOnlyInStock', () => {
                    this.filterProducts();
                });

                // Escuchar el evento después de guardar/actualizar factura
                Livewire.on('updatedORcreated', ({products}) => {
                    // Actualizar la lista local de productos
                    @this.$refresh();
                    
                    // Esperar a que el DOM se actualice
                    this.$nextTick(() => {
                        if (this.tomSelectProduct) {
                            this.tomSelectProduct.destroy();
                            this.initProductSelect(document.getElementById('select-product'));
                        }
                    });
                });

                // Actualizar cuando se crea/actualiza una factura o se actualiza el stock
                Livewire.on('invoice-created/updated', () => {
                    this.$wire.fetchProducts().then(() => {
                        if (this.tomSelectProduct) {
                            const currentSelection = this.tomSelectProduct.getValue();
                            this.tomSelectProduct.clear();
                            this.tomSelectProduct.clearOptions();
                            
                            // Aplicar el filtro actual antes de agregar las opciones
                            const filteredProducts = this.showOnlyInStock 
                                ? @this.products.filter(p => p.stock > 0)
                                : @this.products;
                                
                            this.tomSelectProduct.addOptions(filteredProducts);
                            
                            // Restaurar selección si aún existe y tiene stock
                            if (currentSelection) {
                                const updatedProduct = filteredProducts.find(p => p.id === currentSelection);
                                if (updatedProduct && updatedProduct.stock > 0) {
                                    this.tomSelectProduct.setValue(currentSelection);
                                }
                            }
                        }
                    });
                });
            },

            resetForm() {
                if (this.tomSelectProduct) {
                    this.tomSelectProduct.clear();
                }

                // Resetear todas las variables
                this.selectedProduct = null;
                this.quantity = 0;
                this.availableStock = 0;

                // Forzar actualización de la UI
                this.$nextTick(() => {
                    if (this.tomSelectProduct) {
                        this.tomSelectProduct.refreshOptions(false);
                    }
                });
            },


            initClientSelect(el) {
                this.tomSelectClient = new TomSelect(el, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    options: @json($clients),
                    persist: false,
                    create: false,
                    maxItems: 1,
                    onChange: (value) => {
                        @this.set('form.client_id', value);
                        document.getElementById('client-error').textContent = ''; // Clear error on change
                    }
                });
                Livewire.on('fill-client-select', clientValue => {
                    this.tomSelectClient.setValue(clientValue);
                });

                Livewire.on('reset-client-select', () => {
                    this.tomSelectClient.clear();
                });

                // Listen for validation events
                Livewire.on('validate-client-id', message => {
                    document.getElementById('client-error').textContent = message;
                });

                Livewire.on('clear-validate-client-id', () => {
                    document.getElementById('client-error').textContent = '';
                });
            },

            initProductSelect(el) {
                // Destruir la instancia existente si existe
                if (this.tomSelectProduct) {
                    this.tomSelectProduct.destroy();
                }

                // Nueva inicialización
                this.tomSelectProduct = new TomSelect(el, {
                    valueField: 'id',
                    labelField: 'title',
                    searchField: ['title', 'code', 'description'],
                    options: @json($products),
                    persist: false,
                    create: false,
                    maxItems: 1,
                    render: {
                        option: function(data, escape) {
                            const isOutOfStock = data.stock == 0;
                            return `<div class="flex flex-col py-2 ${isOutOfStock ? 'pointer-events-none' : ''}" role="${isOutOfStock ? 'text' : 'option'}">
                    <div class="flex justify-between items-center ${isOutOfStock ? 'opacity-50' : ''}">
                        <span class="text-sm font-medium dark:text-gray-200">${escape(data.title)}</span>
                        <div class="text-right">
                            <span class="text-xs text-green-600 dark:text-green-400">$${escape(data.price)}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">+${escape(data.vat_percentage)}%</span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 ${isOutOfStock ? 'opacity-50' : ''}">
                        ${escape(data.description)}
                    </div>
                    <div class="text-xs ${isOutOfStock ? 'text-red-500' : 'text-gray-500'} dark:${isOutOfStock ? 'text-red-400' : 'text-gray-400'} mt-1">
                        ${isOutOfStock ? 'Sin stock' : 'Stock: ' + escape(data.stock)}
                    </div>
                </div>`;
                        },
                        item: function(data, escape) {
                            const isOutOfStock = data.stock == 0;
                            return `<div class="flex justify-between items-center ${isOutOfStock ? 'opacity-50' : ''}">
                    <span class="text-sm font-medium dark:text-gray-200">${escape(data.title)}</span>
                </div>`;
                        }
                    },
                    onChange: (value) => {
                        this.selectedProduct = value ? this.tomSelectProduct.options[value] : null;
                        if (value) {
                            this.quantity = 1;
                        } else {
                            this.quantity = 0;
                        }
                        this.updateAvailableStock();
                    },
                    onClear: () => {
                        this.selectedProduct = null;
                        this.quantity = 0; // Resetear a 0 al limpiar
                        this.availableStock = 0;
                    }
                });

                // Aplicar el filtro inicial después de inicializar
                this.$nextTick(() => {
                    this.filterProducts();
                });

                Livewire.on('resetProduct', () => {
                    if (this.tomSelectProduct) {
                        this.tomSelectProduct.clear();
                    }
                });
            },

            filterProducts() {
                if (!this.tomSelectProduct) return;

                // Obtener productos actualizados del componente Livewire
                const allProducts = @this.products;
                const filteredProducts = this.showOnlyInStock
                    ? allProducts.filter(product => product.stock > 0)
                    : allProducts;

                const currentSelection = this.tomSelectProduct.getValue();

                this.tomSelectProduct.clear();
                this.tomSelectProduct.clearOptions();
                this.tomSelectProduct.addOptions(filteredProducts);

                // Restaurar selección si el producto aún está disponible y tiene stock
                if (currentSelection) {
                    const updatedProduct = filteredProducts.find(p => p.id === currentSelection);
                    if (updatedProduct && (!this.showOnlyInStock || updatedProduct.stock > 0)) {
                        this.tomSelectProduct.setValue(currentSelection);
                    }
                }
            },

            updateAvailableStock() {
                if (!this.selectedProduct) {
                    this.availableStock = 0;
                    this.quantity = '';
                    return;
                }

                const usedStock = this.getProductUsedStock(this.selectedProduct.id);
                this.availableStock = this.selectedProduct.stock - usedStock;
            },

            getProductUsedStock(productId) {
                const details = @this.get('form.details') || [];
                return details.reduce((sum, detail) => {
                    return detail.product_id === productId ? sum + detail.quantity : sum;
                }, 0);
            },

            //efreshStockAfterChange() {
            //   if (!this.selectedProduct) return;

            //   // Obtener stock actualizado después de cambios en la tabla de detalles
            //   this.updateAvailableStock();

            //   // Si la cantidad actual es mayor que el nuevo stock disponible, ajustarla
            //   if (this.quantity > this.availableStock) {
            //       this.quantity = this.availableStock;
            //   }
            //,

            incrementQuantity() {
                if (this.quantity < this.availableStock) {
                    this.quantity++;
                }
            },

            decrementQuantity() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },

            validateQuantity(event = null) {
                if (!this.selectedProduct) return;

                let value = event ? event.target.value.trim() : this.quantity.toString();

                if (value === '') {
                    this.quantity = null; // Permitir vacío sin forzar un valor numérico
                    return;
                }

                let numericValue = parseInt(value);

                if (isNaN(numericValue) || numericValue < 1) {
                    this.quantity = null; // Permitir borrar el input sin problemas
                    return;
                }

                if (numericValue > this.availableStock) {
                    this.quantity = this.availableStock; // Ajustar al máximo stock disponible
                } else {
                    this.quantity = numericValue;
                }
            },

            restoreQuantityIfEmpty() {
                if (this.quantity === null || this.quantity === '') {
                    this.quantity = 1; // Si está vacío, establecer en 1
                }
            },

            preventNonNumeric(event) {
                // Permitir solo teclas de números y teclas de control
                if (!/[0-9]/.test(event.key) &&
                    // Permitir teclas de control como backspace, delete, flechas, etc
                    !['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event
                        .key)) {
                    event.preventDefault();
                }
            },

            handlePaste(event) {
                const pastedData = (event.clipboardData || window.clipboardData).getData('text');
                if (/^\d+$/.test(pastedData)) {
                    // Si son solo números, permitir el pegado manual
                    const newValue = parseInt(pastedData);
                    if (newValue > 0) {
                        this.quantity = Math.min(newValue, this.availableStock);
                    }
                }
            },

            preventExcessLength(event) {
                if (!this.selectedProduct) return;

                const currentValue = event.target.value;
                const maxLength = this.availableStock.toString().length;

                // Permitir teclas de control
                if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event
                        .key)) {
                    return;
                }

                // Prevenir la entrada si excedería la longitud máxima
                if (currentValue.length >= maxLength && !event.target.selectionStart !== currentValue.length) {
                    event.preventDefault();
                }
            },

            get canAdd() {
                return this.selectedProduct &&
                    this.quantity !== '' &&
                    this.quantity > 0 &&
                    this.quantity <= this.availableStock;
            },

            async addDetail() {
                if (!this.canAdd) return;

                await @this.call('addDetail', this.selectedProduct.id, this.quantity);
                this.tomSelectProduct.clear();
                this.selectedProduct = null;
                this.quantity = 0; // Resetear a 0 después de agregar
                this.availableStock = 0;
            }
        }
    }
</script>
