<button
    onclick="toggleTheme()"
    {{ $attributes->merge(["class" => "flex items-center space-x-2 px-4 py-4 dark:bg-gray-800 text-yellow-200 dark:text-gray-200 rounded-full hover:bg-slate-100 transition text-xl dark:hover:bg-gray-900"]) }}
>
    <i id="theme-icon" class="fa-solid"></i>
</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const themeIcon = document.getElementById('theme-icon');
        const isDarkNow = document.documentElement.classList.contains('dark');
        
        themeIcon.className = isDarkNow ? 'fa-solid fa-moon' : 'fa-solid fa-sun';

        window.toggleTheme = () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeIcon.className = isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
        };
    });
</script>
