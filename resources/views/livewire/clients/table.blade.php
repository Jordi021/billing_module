<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-b-lg">
    <div class="overflow-hidden">
        <div class="p-4 text-gray-900 dark:text-gray-100 overflow-x-auto flex flex-col items-center">
            @if ($clients->isNotEmpty())
                <!-- Vista móvil, tablet y laptop (tarjetas) -->
                <div class="w-full xl:hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($clients as $client)
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 relative min-h-[280px]">
                            <!-- Contenedor para todo el contenido excepto los botones -->
                            <div class="space-y-3 pb-14"> <!-- pb-14 para dejar espacio a los botones -->
                                <div class="flex justify-between items-center">
                                    <span class="font-bold">#{{ $client['id'] }}</span>
                                    <div class="flex items-center">
                                        <div
                                            class="h-2 w-2 rounded-full {{ $client['status'] == 1 ? 'bg-green-500' : 'bg-red-500' }} me-2">
                                        </div>
                                        {{ $client['status'] == 1 ? __('Active') : __('Inactive') }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Name') }}</p>
                                        <p>{{ $client['name'] }} {{ $client['last_name'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Birthdate') }}</p>
                                        <p>{{ $client['birth_date'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Phone') }}</p>
                                        <p>{{ $client['phone'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Client type') }}</p>
                                        <p>{{ $client['client_type'] }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                                        <p>{{ $client['email'] }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Address') }}</p>
                                        <p>{{ $client['address'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor de botones fijo en la esquina inferior derecha -->
                            <div class="absolute bottom-4 right-4 flex gap-1">
                                <x-custom-button color="bg-indigo-400" icon="fa fa-pencil"
                                    wire:click="$dispatch('client-edit', { client: {{ $client }} })" />
                                @if ($client['status'])
                                    <x-custom-button color="bg-red-400" icon="fas fa-trash"
                                        wire:click="$dispatch('client-confirm', { delete: true, client: {{ $client }} })" />
                                @else
                                    <x-custom-button color="bg-green-400" icon="fas fa-trash-restore"
                                        wire:click="$dispatch('client-confirm', { delete: false, client: {{ $client }} })" />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Vista desktop XL (tabla) -->
                <div class="hidden xl:block w-full">
                    <table class="min-w-full divide-y divide-gray-200 table-auto text-base">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                @foreach (['ID', 'Name', 'Last Name', 'Birthdate', 'Address', 'Phone', 'Email', 'Client type', 'Status', 'Actions'] as $header)
                                    <th scope="col"
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __($header) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            @foreach ($clients as $client)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $client['id'] }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $client['name'] }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $client['last_name'] }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $client['birth_date'] }}</td>
                                    <td class="px-4 py-2 whitespace-normal break-words max-w-[200px]">
                                        {{ $client['address'] }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $client['phone'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $client['email'] }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $client['client_type'] }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-2 w-2 rounded-full {{ $client['status'] == 1 ? 'bg-green-500' : 'bg-red-500' }} me-2">
                                            </div>
                                            {{ $client['status'] == 1 ? __('Active') : __('Inactive') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex gap-1">
                                            <x-custom-button color="bg-indigo-400" icon="fa fa-pencil"
                                                wire:click="$dispatch('client-edit', { client: {{ $client }} })" />
                                            @if ($client['status'])
                                                <x-custom-button color="bg-red-400" icon="fas fa-trash"
                                                    wire:click="$dispatch('client-confirm', { delete: true, client: {{ $client }} })" />
                                            @else
                                                <x-custom-button color="bg-green-400" icon="fas fa-trash-restore"
                                                    wire:click="$dispatch('client-confirm', { delete: false, client: {{ $client }} })" />
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 w-full">
                    {{ $clients->links() }}
                </div>
            @else
                <p class="text-center text-gray-500 dark:text-gray-300">
                    {{ __('No clients available.') }}
                </p>
            @endif
        </div>
    </div>
</div>
