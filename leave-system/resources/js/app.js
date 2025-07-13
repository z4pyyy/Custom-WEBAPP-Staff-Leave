import './bootstrap';

// AdminLTE & dependencies
import 'admin-lte';
import 'admin-lte/dist/css/adminlte.min.css';

import 'bootstrap';
import 'jquery';
import $ from 'jquery';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';

import '@fortawesome/fontawesome-free/css/all.min.css';
import 'icheck-bootstrap/icheck-bootstrap.min.css';

import Alpine from 'alpinejs';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

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

// Bootstrap dropdown for notification
document.addEventListener('DOMContentLoaded', function () {
    $('[data-toggle="dropdown"]').dropdown();
});

// FullCalendar
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        height: 650,

        events: {
            url: '/leave/calendar/data',
            method: 'GET',
            failure: () => alert('Error fetching events!')
        },

        eventDidMount(info) {
            const tooltipText = `Reason: ${info.event.extendedProps.reason}\nStatus: ${info.event.extendedProps.status}`;
            info.el.setAttribute('title', tooltipText);
        },

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'customMonthYear' // 用来自定义月年选择器
        }
    });

    calendar.render();

    setTimeout(() => {
        const rightToolbar = document.querySelector('.fc-customMonthYear-button');
        if (!rightToolbar) return;

        // 📦 外容器
        const wrapper = document.createElement('div');
        wrapper.style.display = 'flex';
        wrapper.style.gap = '6px';
        wrapper.style.alignItems = 'center';

        const currentDate = calendar.getDate();

        // ================= Month Select =================
        const monthNames = Array.from({ length: 12 }, (_, i) =>
            new Date(0, i).toLocaleString("default", { month: "long" })
        );

        const monthSelect = document.createElement('select');
        monthSelect.className = 'form-select form-select-sm';
        monthSelect.style.width = 'auto';
        monthSelect.style.padding = '4px 8px';
        monthSelect.style.border = '1px solid #ccc';
        monthSelect.style.boxShadow = 'none';
        monthSelect.style.outline = 'none';
        monthSelect.style.backgroundColor = 'white';
        monthSelect.style.color = 'black';

        monthNames.forEach((name, i) => {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = name;
            if (i === currentDate.getMonth()) opt.selected = true;
            monthSelect.appendChild(opt);
        });

        monthSelect.addEventListener('change', () => {
            const newDate = new Date(calendar.getDate());
            newDate.setMonth(parseInt(monthSelect.value));
            calendar.gotoDate(newDate);
        });

        // ================= Year Select =================
        const yearSelect = document.createElement('select');
        yearSelect.className = 'form-select form-select-sm';
        yearSelect.style.width = '80px';
        yearSelect.style.padding = '4px 8px';
        yearSelect.style.border = '1px solid #ccc';
        yearSelect.style.boxShadow = 'none';
        yearSelect.style.outline = 'none';
        yearSelect.style.backgroundColor = 'white';
        yearSelect.style.color = 'black';

        for (let y = 2000; y <= 2100; y++) {
            const opt = document.createElement('option');
            opt.value = y;
            opt.textContent = y;
            if (y === currentDate.getFullYear()) opt.selected = true;
            yearSelect.appendChild(opt);
        }

        yearSelect.addEventListener('change', () => {
            const newDate = new Date(calendar.getDate());
            newDate.setFullYear(parseInt(yearSelect.value));
            calendar.gotoDate(newDate);
        });

        // Insert DOM
        wrapper.appendChild(monthSelect);
        wrapper.appendChild(yearSelect);

        rightToolbar.innerHTML = '';
        rightToolbar.replaceWith(wrapper);
    }, 50);
});

