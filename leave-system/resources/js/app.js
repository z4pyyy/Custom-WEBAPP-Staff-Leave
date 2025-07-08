import './bootstrap';

// AdminLTE & dependencies
import 'admin-lte';
import 'admin-lte/dist/css/adminlte.min.css';

import 'bootstrap';
import 'jquery';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';

import '@fortawesome/fontawesome-free/css/all.min.css';
import 'icheck-bootstrap/icheck-bootstrap.min.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Sidebar toggle script
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-submenu').forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const parent = toggle.closest('.nav-item');
            const submenu = parent.querySelector('.nav-treeview');
            const icon = toggle.querySelector('.arrow-icon');

            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
                icon.classList.remove('fa-angle-down');
                icon.classList.add('fa-angle-left');
            } else {
                submenu.style.display = 'block';
                icon.classList.remove('fa-angle-left');
                icon.classList.add('fa-angle-down');
            }
        });
    });
});


