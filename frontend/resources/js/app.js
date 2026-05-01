import './bootstrap';

// Page-specific scripts are loaded by each page's own @vite() directive.
// Importing them here would cause them to run twice on every page,
// double-binding click handlers and duplicating form submissions.

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
