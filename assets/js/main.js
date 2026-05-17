/**
 * SMM Panel - Main JavaScript
 * Complete overhaul with mobile menu, animations, dark mode, FOMO, etc.
 */

(function() {
    'use strict';

    // ===========================
    // MOBILE HAMBURGER MENU
    // ===========================
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const body = document.body;

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
            body.classList.toggle('menu-open');
        });

        // Close on nav link click
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                body.classList.remove('menu-open');
            });
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                body.classList.remove('menu-open');
            }
        });
    }

    // ===========================
    // STICKY HEADER
    // ===========================
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // ===========================
    // SCROLL ANIMATIONS
    // ===========================
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.service-card, .feature-card, .step, .info-card, .stat');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animated');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        elements.forEach(el => {
            el.classList.add('fade-in-up');
            observer.observe(el);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', animateOnScroll);
    } else {
        animateOnScroll();
    }

    // ===========================
    // COUNTER ANIMATION
    // ===========================
    const animateCounters = () => {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const update = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(update);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };
            update();
        });
    };

    const statsSection = document.querySelector('.hero-stats');
    if (statsSection) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                animateCounters();
                observer.unobserve(statsSection);
            }
        }, { threshold: 0.5 });
        observer.observe(statsSection);
    }

    // ===========================
    // FAQ ACCORDION
    // ===========================
    document.querySelectorAll('.faq-item summary').forEach(summary => {
        summary.addEventListener('click', function(e) {
            e.preventDefault();
            this.parentElement.classList.toggle('open');
        });
    });

    // ===========================
    // PRICE CALCULATOR
    // ===========================
    const serviceSelect = document.getElementById('serviceSelect');
    const quantityInput = document.getElementById('quantity');
    const totalPrice = document.getElementById('totalPrice');

    if (serviceSelect && quantityInput && totalPrice) {
        const updatePrice = () => {
            const option = serviceSelect.options[serviceSelect.selectedIndex];
            const rate = parseFloat(option?.dataset?.rate || 0);
            const qty = parseInt(quantityInput.value || 0);
            totalPrice.textContent = '$' + (rate * qty).toFixed(2);
        };

        serviceSelect.addEventListener('change', updatePrice);
        quantityInput.addEventListener('input', updatePrice);
    }

    // ===========================
    // PASSWORD TOGGLE
    // ===========================
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.textContent = isPassword ? '🙈' : '👁️';
        });
    });

    // ===========================
    // PASSWORD STRENGTH
    // ===========================
    const passwordInput = document.getElementById('password');
    const strengthBar = document.querySelector('.strength-bar');

    if (passwordInput && strengthBar) {
        passwordInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 8) strength++;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) strength++;
            if (val.match(/\d/)) strength++;
            if (val.match(/[^a-zA-Z\d]/)) strength++;

            strengthBar.style.width = (strength * 25) + '%';
            strengthBar.style.backgroundColor = ['#ef4444', '#f59e0b', '#10b981', '#6366f1'][strength - 1] || '#ef4444';
        });
    }

    // ===========================
    // COOKIE CONSENT
    // ===========================
    const cookieBanner = document.querySelector('.cookie-banner');
    const acceptBtn = document.getElementById('acceptCookies');

    if (cookieBanner && acceptBtn && !localStorage.getItem('cookiesAccepted')) {
        cookieBanner.style.display = 'block';
        acceptBtn.addEventListener('click', () => {
            localStorage.setItem('cookiesAccepted', 'true');
            cookieBanner.style.display = 'none';
        });
    }

    // ===========================
    // DARK MODE
    // ===========================
    const darkToggle = document.getElementById('darkModeToggle');
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.body.classList.add('dark-mode');
    }

    if (darkToggle) {
        darkToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            // Toggle icon
            const moonIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
            const sunIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
            darkToggle.innerHTML = isDark ? sunIcon : moonIcon;
        });
    }

    // ===========================
    // ANNOUNCEMENT BAR
    // ===========================
    const announcementClose = document.querySelector('.announcement-close');
    const announcementBar = document.querySelector('.announcement-bar');

    if (announcementClose && announcementBar && !localStorage.getItem('announcementDismissed')) {
        announcementClose.addEventListener('click', () => {
            announcementBar.style.display = 'none';
            localStorage.setItem('announcementDismissed', 'true');
        });
    }

    // ===========================
    // COUNTDOWN TIMER
    // ===========================
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        // Get stored end time or set new one (24 hours from now)
        let endTime = localStorage.getItem('promoCountdownEnd');
        if (!endTime) {
            endTime = Date.now() + 24 * 60 * 60 * 1000;
            localStorage.setItem('promoCountdownEnd', endTime);
        }

        const updateCountdown = () => {
            const remaining = parseInt(endTime) - Date.now();
            if (remaining <= 0) {
                // Reset for new day
                endTime = Date.now() + 24 * 60 * 60 * 1000;
                localStorage.setItem('promoCountdownEnd', endTime);
                return;
            }

            const hours = Math.floor(remaining / (1000 * 60 * 60));
            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

            countdownEl.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        };

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // ===========================
    // SMOOTH SCROLL
    // ===========================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

})();