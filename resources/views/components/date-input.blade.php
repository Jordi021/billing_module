@props(['label', 'name', 'model' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ $label }}
    </label>
    <input
        {{ $attributes->merge([
            'class' => 'border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm p-3 w-full'
        ]) }}
        type="date"
        id="{{ $name }}"
        name="{{ $name }}"
    />
</div>
