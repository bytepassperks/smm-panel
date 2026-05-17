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
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
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