<div class="p-6">
    <div class="animate-pulse">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Header skeleton -->
                <thead>
                    <tr>
                        <th class="px-6 py-3 w-1/4">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </th>
                        <th class="px-6 py-3 w-1/6">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </th>
                        <th class="px-6 py-3 w-1/6">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </th>
                        <th class="px-6 py-3 w-1/6">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </th>
                        <th class="px-6 py-3 w-1/6">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </th>
                        <th class="px-6 py-3 w-1/6">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </th>
                    </tr>
                </thead>
                <!-- Body skeleton -->
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @for ($i = 0; $i < $detailsCount; $i++) <!-- Assuming you have 5 rows -->
                        <tr>
                            <td class="px-6 py-4">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </td>
                        </tr>
                    @endfor
                </tbody>
                <!-- Footer skeleton -->
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td colspan="3" class="px-6 py-4"></td>
                        <td class="px-6 py-4">
                            <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded"></div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <div class="h-8 w-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
    </div>
</div>
