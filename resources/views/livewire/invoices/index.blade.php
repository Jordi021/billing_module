<x-app-layout>
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end mb-0 mt-6">
            <!-- Header Tab -->
            <div
                class="inline-flex items-center space-x-2 bg-white dark:bg-gray-800 px-6 dark:active:bg-green-200 active:bg-green-600 py-2 rounded-t-lg border-t border-l border-r border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                    {{ __('Manage invoices') }}
                </h2>
            </div>
        </div>

        <!-- Filters Section -->
        <livewire:invoices.actions-filters />

        <!-- Table Section -->
        <livewire:invoices.table />

        {{-- <form action="#">
            <div class="mb-4">
                <select id="select-client2" name="client_id" multiple autocomplete="off">
    
                </select>
            </div>
        </form> --}}

    </div>

    <!-- Modal Component -->
    <x-modal name="invoice-modal" :show="false" focusable>
        <livewire:invoices.modal />
    </x-modal>
    <x-modal name="client-modal" :show="false" focusable>
        <livewire:invoices.client-modal />
    </x-modal>
    <x-modal name="details-modal" :show="false" focusable>
        <livewire:invoices.details-modal />
    </x-modal>
    <x-modal name="invoice-modal-confirmation" :show="false" focusable>
        <livewire:invoices.confirmation-modal />
    </x-modal>

    {{-- <script>
    
        const settings = {
            plugins: ['remove_button'],
            persist: false,
            create: true,
            maxItems: 1,
        };
        new TomSelect('#select-client2',settings);
    
    </script> --}}
    
</x-app-layout>
