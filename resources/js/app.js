import './bootstrap';

const toggleTheme = () => {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');

    isDark
        ? localStorage.setItem('theme', 'dark')
        : localStorage.setItem('theme', 'light');
};

window.toggleTheme = toggleTheme;

document.addEventListener('DOMContentLoaded', () => {
    const html = document.documentElement;

    const isDark =
        localStorage.theme === 'dark' ||
        (!('theme' in localStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches);

    html.classList.toggle('dark', isDark);
});
