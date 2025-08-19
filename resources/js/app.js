import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    const sidebar = document.querySelector('aside');

    // Load dark mode preference from local storage
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        darkModeToggle.checked = true;
    }

    // Dark mode toggle functionality
    darkModeToggle.addEventListener('change', function() {
        if (this.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'disabled');
        }
    });

    // Toggle sidebar on smaller screens
    const menuButton = document.querySelector('[aria-controls="sidebar"]');
    if (menuButton) {
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    // Close sidebar when clicking outside on smaller screens
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768 && !sidebar.contains(event.target) && !menuButton.contains(event.target)) {
            sidebar.classList.add('-translate-x-full');
        }
    });

    // Adjust sidebar visibility on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
        }
    });
});