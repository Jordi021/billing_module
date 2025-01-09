<button {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center size-8 rounded-full font-semibold text-xs text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ' . 
    ($color ?? 'bg-gray-800') . 
    ' hover:bg-opacity-80 focus:ring-opacity-50'
]) }}>
    <!-- Icono de Font Awesome -->
    <i class="{{ $icon ?? '' }}"></i>
</button>
