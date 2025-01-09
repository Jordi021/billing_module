@props(['id' => 'floating_outlined', 'label' => 'Floating outlined', 'type' => 'text'])

<div class="relative">
    <input type="{{ $type }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-0 focus:border-indigo-500 dark:focus:border-indigo-600 peer']) }} placeholder=" " />
    <label for="{{ $id }}" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-indigo-500 peer-focus:dark:text-indigo-600 
    rounded-lg translate-x-1
    peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">{{ $label }}</label>
</div>
