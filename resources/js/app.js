// app.js
import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;
 
// Theme - Dark mode
if (localStorage.theme === 'dark') {    // || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}