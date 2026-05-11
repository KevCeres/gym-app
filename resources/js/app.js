import './bootstrap';

import Alpine from 'alpinejs';

window.toggleTheme = function () {
    const root = document.documentElement;
    const nextDark = !root.classList.contains('dark');
    root.classList.toggle('dark', nextDark);
    localStorage.setItem('theme', nextDark ? 'dark' : 'light');
};

window.Alpine = Alpine;

Alpine.start();
