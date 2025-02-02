<div x-data="{ isLoading: true }" 
     x-init="$watch('$wire.isLoading', value => isLoading = value)">
    <div class="p-6">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">
            {{ __('Client Details') }}
        </h2>
        @if($isLoading)
        <div
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            @include('livewire.placeholders.client-modal-skeleton')
        </div>
        @else
        <div
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            @if ($client)
                <div class="space-y-4"> {{-- Use space-y for vertical spacing --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-label value="{{ __('ID') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->id }}</p>
                        </div>
                        <div>
                            <x-label value="{{ __('Name') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->name }}</p>
                        </div>
                        <div>
                            <x-label value="{{ __('Last Name') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->last_name }}</p>
                        </div>
                        <div>
                            <x-label value="{{ __('Email') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->email }}</p>
                        </div>
                        <div>
                            <x-label value="{{ __('Phone') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->phone }}</p>
                        </div>
                        <div>
                            <x-label value="{{ __('Client Type') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->client_type }}</p>
                        </div>
                        <div>
                            <x-label value="{{ __('Address') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->address }}</p>
                        </div>
                        <div>
                            <x-label value="{{ __('Birthdate') }}" />
                            <p class="text-gray-700 dark:text-gray-400 font-medium">{{ $client->birth_date }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-8 flex justify-end"> {{-- Increased margin --}}
            <x-secondary-button wire:click.prevent="closeModal">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
        @endif
    </div>
</div>
