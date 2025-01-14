<button
    {{ $attributes->merge([
        'class' =>
            'inline-flex items-center justify-center size-8 rounded-full font-semibold text-xs text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ' .
            ($color ?? 'bg-gray-800') .
            ' hover:bg-opacity-80 focus:ring-opacity-50',
    ]) }}>
    @if (!empty($img))
        <!-- Display the image -->
        <img src="{{ asset($img) }}" alt="Button Image" class="w-5 h-5">
    @endif

    @if (!empty($icon))
        <!-- Display the font awesome icon -->
        <i class="{{ $icon }}"></i>
    @endif

    {{ $slot }}
</button>
