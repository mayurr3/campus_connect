// ==========================================================
// Campus Connect — Frontend JavaScript Functions
// File: assets/js/script.js
// Purpose: Interactive UI, live search, filtering, confirmation
// ==========================================================

document.addEventListener('DOMContentLoaded', () => {
    
    // ------------------------------------------------------
    // 1. Mobile Navigation Toggle
    // ------------------------------------------------------
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('mobile-open');
        });
    }

    // ------------------------------------------------------
    // 2. Password Visibility Toggle (Clean SVG Icons)
    // ------------------------------------------------------
    const eyeSvg = `<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
    const eyeOffSvg = `<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>`;

    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.innerHTML = eyeSvg;
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = eyeOffSvg;
                    this.setAttribute('title', 'Hide Password');
                } else {
                    input.type = 'password';
                    this.innerHTML = eyeSvg;
                    this.setAttribute('title', 'Show Password');
                }
            }
        });
    });

    // ------------------------------------------------------
    // 3. Client-Side Event Live Search & Category Filter
    // ------------------------------------------------------
    const searchInput = document.getElementById('eventSearch');
    const filterBtns = document.querySelectorAll('.cat-btn');
    const eventCards = document.querySelectorAll('.event-card');
    const noEventsMsg = document.getElementById('noEventsMessage');

    let currentCategory = 'all';
    let currentSearchTerm = '';

    function filterEvents() {
        let visibleCount = 0;

        eventCards.forEach(card => {
            const title = (card.getAttribute('data-title') || '').toLowerCase();
            const category = (card.getAttribute('data-category') || '').toLowerCase();

            const matchesCategory = (currentCategory === 'all' || category === currentCategory);
            const matchesSearch = title.includes(currentSearchTerm);

            if (matchesCategory && matchesSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noEventsMsg) {
            noEventsMsg.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            currentSearchTerm = e.target.value.toLowerCase().trim();
            filterEvents();
        });
    }

    if (filterBtns.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentCategory = (this.getAttribute('data-category') || 'all').toLowerCase();
                filterEvents();
            });
        });
    }

    // ------------------------------------------------------
    // 4. Form Validation Helper
    // ------------------------------------------------------
    const formsToValidate = document.querySelectorAll('.needs-validation');
    formsToValidate.forEach(form => {
        form.addEventListener('submit', function(e) {
            const emailInput = form.querySelector('input[type="email"]');
            const passwordInput = form.querySelector('input[name="password"]');
            const phoneInput = form.querySelector('input[name="phone"]');

            if (emailInput && !emailInput.value.includes('@')) {
                alert('Please enter a valid email address.');
                e.preventDefault();
                emailInput.focus();
                return false;
            }

            if (passwordInput && passwordInput.value.length < 6) {
                alert('Password must be at least 6 characters long.');
                e.preventDefault();
                passwordInput.focus();
                return false;
            }

            if (phoneInput && phoneInput.value && !/^\d{10}$/.test(phoneInput.value.trim())) {
                alert('Please enter a valid 10-digit phone number.');
                e.preventDefault();
                phoneInput.focus();
                return false;
            }
        });
    });
});

// ----------------------------------------------------------
// 5. Delete Confirmation Dialog (Global Helper)
// ----------------------------------------------------------
function confirmDelete(message = 'Are you sure you want to delete this?') {
    return confirm(message);
}
