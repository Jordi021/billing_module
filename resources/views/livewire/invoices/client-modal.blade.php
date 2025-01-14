<div>
    <div class="p-6">
        <h2
            class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">
            {{ __('Client Details') }}
        </h2>

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
        @else
            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mx-auto animate-pulse mb-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M16.023 16.023v-.002" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 11.5h-5c-1.103 0-2-.897-2-2v-1c0-1.103.897-2 2-2h5c1.103 0 2 .897 2 2v1c0 1.103-.897 2-2 2zM9 13h6c.553 0 1 .448 1 1v5c0 .552-.447 1-1 1H9a1 1 0 01-1-1v-5c0-.552.447-1 1-1z" />
                </svg>
                <span>Loading client information...</span>
            </div>
        @endif

        <div class="mt-8 flex justify-end"> {{-- Increased margin --}}
            <x-secondary-button wire:click.prevent="closeModal">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
    </div>
</div>
