<div class="p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Delete invoice') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Are you sure you want to delete this invoice?') }}
    </p>


    <div class="mt-6 flex justify-end">
        <x-secondary-button x-on:click="$dispatch('close')">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-danger-button class="ml-3" wire:click="deleteInvoice">
            {{ __('Delete') }}
        </x-danger-button>
    </div>
</div>
