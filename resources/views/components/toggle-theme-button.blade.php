<!-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca -->
<button
    onclick="toggleTheme()"
    {{ $attributes->merge(["class" => "flex items-center space-x-2 px-4 py-4 dark:bg-gray-800 text-yellow-200 dark:text-gray-200 rounded-full hover:bg-slate-100 transition text-xl dark:hover:bg-gray-900"]) }}
>
    <i id="theme-icon" class="fa-solid"></i>
</button>

