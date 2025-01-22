<x-app-layout :title="__('Manage Clients')">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end mb-0 mt-6">
            <!-- Header Tab -->
            <div
                class="inline-flex items-center space-x-2 bg-white dark:bg-gray-800 px-6 dark:active:bg-green-200 active:bg-green-600 py-2 rounded-t-lg border-t border-l border-r border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                    {{ __('Manage Clients') }}
                </h2>
            </div>
        </div>
        <!-- Filters Section -->
        <livewire:clients.actions-filters />

        <!-- Table Section -->
        <livewire:clients.table />
    </div>

    <!-- Modal Component -->
    <x-modal name="client-modal" :show="false" focusable>
        <livewire:clients.modal />
    </x-modal>
    <x-modal name="client-modal-confirmation" maxWidth="lg" :show="false" focusable>
        <livewire:clients.confirmation-modal />
    </x-modal>
</x-app-layout>
