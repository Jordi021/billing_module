<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-b-lg">
    <div class="overflow-hidden">
        <div class="p-4 text-gray-900 dark:text-gray-100 overflow-x-auto flex flex-col items-center">
            @if ($clients->isNotEmpty())
                <table class="min-w-full divide-y divide-gray-200 table-auto text-base">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @foreach(['ID', 'Name', 'Last Name', 'Birthdate', 'Address', 'Phone', 'Email', 'Client type', 'Status', 'Actions'] as $header)
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
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
                                <td class="px-4 py-2 whitespace-normal break-words max-w-[200px]">{{ $client['address'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $client['phone'] ?? 'N/A' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $client['email'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ __($client['client_type']) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 rounded-full {{ $client['status'] == 1 ? 'bg-green-500' : 'bg-red-500' }} me-2"></div>
                                        {{ $client['status'] == 1 ? __('Active') : __('Inactive') }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <x-custom-button 
                                            color="bg-indigo-400" 
                                            icon="fa fa-pencil" 
                                            wire:click="$dispatch('client-edit', { client: {{$client}} })"
                                        />
                                    @if ($client['status'])
                                        <x-custom-button 
                                            color="bg-red-400" 
                                            icon="fas fa-trash" 
                                            wire:click="$dispatch('client-confirm', { delete: true, client: {{$client}} })"
                                        />
                                    @else
                                        <x-custom-button 
                                            color="bg-green-400" 
                                            icon="fas fa-trash-restore" 
                                            wire:click="$dispatch('client-confirm', { delete: false, client: {{$client}} })"
                                        />
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
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
