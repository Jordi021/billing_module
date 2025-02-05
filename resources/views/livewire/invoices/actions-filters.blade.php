<div class="bg-white dark:bg-gray-800 px-4 py-4 border-b border-gray-200 dark:border-gray-700 mb-6 relative">
    <button wire:click="resetFilters"
        class="absolute top-40 sm:top-32 md:top-20 lg:top-14 xl:top-0 right-4 bg-red-500 px-1 hover:bg-red-600 text-white hover:text-gray-100">
        <i class="fas fa-times"></i>
    </button>

    <!-- Contenedor principal: apilado en móvil, flex en xl -->
    <div class="flex flex-col space-y-6 xl:space-y-0 xl:flex-row xl:items-center xl:justify-between">
        <!-- Sección de botones: 1 columna móvil, 2 en sm, row en md -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:flex md:flex-row gap-4 md:gap-4">
            <div class="w-full sm:w-auto">
                <x-primary-button
                    class="w-full sm:w-36 justify-center gap-2 bg-green-400 hover:bg-green-500 dark:bg-green-300 focus:bg-green-600 active:bg-green-600 dark:active:bg-green-200 dark:focus:bg-green-200 dark:hover:bg-green-400 focus:ring-green-600"
                    x-on:click.prevent="$dispatch('open-modal', 'invoice-modal')">
                    {{ __('Add') }} <i class="fa-solid fa-plus"></i>
                </x-primary-button>
            </div>
            <div class="w-full sm:w-auto">
                <button wire:click="generateReport"
                    class="w-full sm:w-36 justify-center gap-2 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-300">
                    {{ __('Report') }}<i class="fa-solid fa-file-pdf"></i>
                </button>
            </div>

            <div class="w-full sm:w-auto relative">
                <!-- Nuevo botón tab de historial -->
                <div class="absolute -top-5 right-2 sm:right-[148px] md:right-2">
                    <button wire:click="$dispatch('reset-advance-filters')"
                        class="px-1 pt-[2px] text-xs bg-amber-500 hover:bg-amber-600 dark:bg-amber-300 dark:hover:bg-amber-400 text-white dark:text-gray-800 rounded-t-md transition-colors"
                        title="{{ __('Reset advanced filters') }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </button>
                </div>
                <button
                    class="w-full sm:w-36 inline-flex items-center justify-center px-4 py-2 gap-2 bg-amber-500 dark:bg-amber-300 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-amber-600 dark:hover:bg-amber-500 focus:bg-amber-500 dark:focus:bg-amber-500 active:bg-amber-600 dark:active:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-amber-800 transition ease-in-out duration-300"
                    x-on:click.prevent="$dispatch('open-modal', 'advance-filters-modal')">
                    {{ __('Advanced') }} <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </div>

        <!-- Sección de filtros -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:flex xl:items-center gap-4 xl:space-x-4">
            <!-- Client Status -->
            <div class="w-full sm:w-auto space-y-1">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Client Status') }}
                </label>
                <select wire:model.live="status" id="status" name="status"
                    class="w-full sm:w-52 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-0 focus:border-indigo-500 dark:focus:border-indigo-600">
                    <option value="all">{{ __('All') }}</option>
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>

            <!-- Payment Type -->
            <div class="w-full sm:w-auto space-y-1">
                <label for="payment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Payment Type') }}
                </label>
                <select wire:model.live="payment_type" id="payment_type" name="payment_type"
                    class="w-full sm:w-52 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-0 focus:border-indigo-500 dark:focus:border-indigo-600">
                    <option value="all">{{ __('All') }}</option>
                    <option value="Cash">{{ __('Cash') }}</option>
                    <option value="Credit">{{ __('Credit') }}</option>
                </select>
            </div>

            <!-- Search invoices -->
            <div class="w-full sm:col-span-2 lg:col-span-1 lg:self-end xl:w-80">
                <x-text-float-input type="text" id="search" name="search" wire:model.live="search"
                    label="{{ __('Search invoice') }}" class="w-full px-6 py-3" />
            </div>
        </div>
    </div>
</div>
