import './bootstrap';
// import 'tom-select/dist/css/tom-select.css';
import 'tom-select/dist/css/tom-select.bootstrap5.css';
import TomSelect from 'tom-select';

const html = document.documentElement;

const ICONS = {
    dark: 'fa-solid fa-moon',
    light: 'fa-solid fa-sun',
};

const updateThemeIcons = (isDark) => {
    const themeIcons = document.querySelectorAll('.theme-icon');
    themeIcons.forEach(icon => {
        icon.className = `theme-icon ${isDark ? ICONS.dark : ICONS.light}`;
    });
};

window.toggleTheme = () => {
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('dark', isDark);
    updateThemeIcons(isDark);
    
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark } }));
};

document.addEventListener('DOMContentLoaded', () => {
    const isDark = localStorage.getItem('dark') === 'true';

    html.classList.toggle('dark', isDark);
    updateThemeIcons(isDark);
});

window.TomSelect = TomSelect;
