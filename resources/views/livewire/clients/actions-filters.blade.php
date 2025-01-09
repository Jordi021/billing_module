<div class="bg-white dark:bg-gray-800 px-4 py-4 border-b border-gray-200 dark:border-gray-700 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div>
                <x-primary-button class="w-36 justify-center gap-2 bg-green-400 hover:bg-green-500 dark:bg-green-300 focus:bg-green-600 active:bg-green-600 dark:active:bg-green-200 dark:focus:bg-green-200 dark:hover:bg-green-400 focus:ring-green-600" x-on:click.prevent="$dispatch('open-modal', 'client-modal')">
                    {{ __('Add') }} <i class="fa-solid fa-user-plus"></i>
                </x-primary-button>
            </div>
            <div>
                <a href="{{ URL('clients/pdf') }}" class="w-36 justify-center gap-2 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-300">{{ __('Report') }}<i class="fa-solid fa-file-pdf"></i></a>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Client Status -->
            <div class="mb-4 space-y-1">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Client Status') }}
                </label>
                <select wire:model.live="status" id="status" name="status" class="w-full md:w-52 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-0 focus:border-indigo-500 dark:focus:border-indigo-600">
                    <option value="all">{{ __('All') }}</option> 
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>

            <!-- Client Type -->
            <div class="mb-4 space-y-1">
                <label for="client_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Client Type') }}
                </label>
                <select wire:model.live="client_type" id="client_type" name="client_type" class="w-full md:w-52 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-0 focus:border-indigo-500 dark:focus:border-indigo-600">
                    <option value="all">{{ __('All') }}</option>
                    <option value="Cash">{{ __('Cash') }}</option>
                    <option value="Credit">{{ __('Credit') }}</option>
                </select>
            </div>

            <!-- Search Client -->
            <div class="w-full md:w-3/4 lg:w-3/4">
                <x-text-float-input
                    type="text"
                    id="search"
                    name="search"
                    wire:model.live="search"
                    label="{{ __('Search client') }}"
                    class="px-6 py-3"
                />
            </div>
        </div>
    </div>
</div>
