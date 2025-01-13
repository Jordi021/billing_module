import './bootstrap';

const html = document.documentElement;

const ICONS = {
    dark: 'fa-solid fa-moon',
    light: 'fa-solid fa-sun',
};

const updateThemeIcon = (isDark) => {
    const themeIcon = document.getElementById('theme-icon');
    if (!themeIcon) return;
    themeIcon.className = isDark ? ICONS.dark : ICONS.light;
};

window.toggleTheme = () => {
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('dark', isDark);
    updateThemeIcon(isDark);
};

document.addEventListener('DOMContentLoaded', () => {
    const isDark = localStorage.getItem('dark') === 'true';

    html.classList.toggle('dark', isDark);
    updateThemeIcon(isDark);
});

