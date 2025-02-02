<div class="animate-pulse">
    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach (['ID', 'Name', 'Last Name', 'Email', 'Phone', 'Client Type', 'Address', 'Birthdate'] as $field)
                <div>
                    <div class="h-4 w-16 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-8 flex justify-end">
        <div class="h-8 w-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
    </div>
</div>
