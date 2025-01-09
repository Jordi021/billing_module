import './bootstrap';

function toggleTheme() {
    const html = document.documentElement;
    const themeIcon = document.getElementById("theme-icon");
    const isDark = html.classList.toggle("dark");

    if (isDark) {
        localStorage.setItem("theme", "dark");
        themeIcon.classList.remove("fa-sun");
        themeIcon.classList.add("fa-moon");
    } else {
        localStorage.setItem("theme", "light");
        themeIcon.classList.remove("fa-moon");
        themeIcon.classList.add("fa-sun");
    }
}

window.toggleTheme = toggleTheme;

document.addEventListener("DOMContentLoaded", () => {
    const html = document.documentElement;
    const themeIcon = document.getElementById("theme-icon");

    const isDark =
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches);

    html.classList.toggle("dark", isDark);

    if (isDark) {
        themeIcon.classList.remove("fa-sun");
        themeIcon.classList.add("fa-moon");
    } else {
        themeIcon.classList.remove("fa-moon");
        themeIcon.classList.add("fa-sun");
    }
});
