<div class="p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
        {{ __('Advanced Filters') }}
    </h2>

    <div class="space-y-6">
        <!-- Client Selection -->
        <x-select-input label="{{ __('Select Client') }}" name="client_id" wire:model.live="client_id">
            <option value="">{{ __('All Clients') }}</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}">
                    {{ $client->id }} - {{ mb_substr($client->name, 0, 1, 'UTF-8') }}. {{ $client->last_name }}
                </option>
            @endforeach
        </x-select-input>

        <!-- Date Range -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-date-input label="{{ __('Start Date') }}" name="start_date" wire:model.blur="start_date" />
            <x-date-input label="{{ __('End Date') }}" name="end_date" wire:model.blur="end_date" />
        </div>

        <!-- Status -->
        <x-select-input label="{{ __('Client Status') }}" name="status" wire:model.live="status">
            <option value="all">{{ __('All') }}</option>
            <option value="1">{{ __('Active') }}</option>
            <option value="0">{{ __('Inactive') }}</option>
        </x-select-input>

        <!-- Payment Type -->
        <x-select-input label="{{ __('Payment Type') }}" name="payment_type" wire:model.live="payment_type">
            <option value="all">{{ __('All') }}</option>
            <option value="Cash">{{ __('Cash') }}</option>
            <option value="Credit">{{ __('Credit') }}</option>
        </x-select-input>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3">
            <x-secondary-button x-on:click="$dispatch('close-modal', 'advance-filters-modal')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button wire:click="resetFilters">
                {{ __('Reset') }}
            </x-danger-button>
            <x-primary-button wire:click="applyFilters">
                {{ __('Apply') }}
            </x-primary-button>
        </div>
    </div>
</div>
