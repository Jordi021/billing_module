<div class="p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $delete ? __('Deactivate Client') : __('Activate Client') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $delete 
            ? __('Are you sure you want to deactivate this client?') 
            : __('Are you sure you want to activate this client?') 
        }}
    </p>

    <div class="mt-6 flex justify-end">
        <x-secondary-button x-on:click="$dispatch('close')">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-danger-button class="ml-3" wire:click="updateClientStatus">
            {{ $delete ? __('Deactivate') : __('Activate') }}
        </x-danger-button>
    </div>
</div>

