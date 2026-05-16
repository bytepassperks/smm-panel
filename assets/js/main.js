/**
 * SMM Panel - Main JavaScript
 * Production-ready JS with error handling and SEO-friendly behavior
 */

(function() {
    'use strict';

    // =====================================================
    // Utilities
    // =====================================================

    /**
     * Get element by ID
     */
    const $ = (id) => document.getElementById(id);

    /**
     * Fetch wrapper with error handling
     */
    async function apiFetch(url, options = {}) {
        try {
            const response = await fetch(url, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...options.headers
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // =====================================================
    // Order Form Handler
    // =====================================================

    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = orderForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            const formData = {
                service_id: parseInt(orderForm.service_id.value),
                link: orderForm.link.value.trim(),
                quantity: parseInt(orderForm.quantity.value)
            };

            // Add optional fields
            if (orderForm.runs) formData.runs = parseInt(orderForm.runs.value) || null;
            if (orderForm.interval) formData.interval = parseInt(orderForm.interval.value) || null;
            if (orderForm.custom_comments) formData.custom_comments = orderForm.custom_comments.value;

            try {
                const result = await apiFetch('/api/order.php', {
                    method: 'POST',
                    body: JSON.stringify(formData)
                });

                if (result.success) {
                    showNotification('Order placed successfully!', 'success');
                    orderForm.reset();
                    updateBalance(result.data.remaining_balance);
                }
            } catch (error) {
                showNotification(error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }

    // =====================================================
    // Service Quantity Calculator
    // =====================================================

    const quantityInputs = document.querySelectorAll('input[name="quantity"]');
    const priceDisplay = document.getElementById('priceDisplay');
    const serviceRateInput = document.getElementById('serviceRate');

    if (quantityInputs.length && priceDisplay && serviceRateInput) {
        quantityInputs.forEach(input => {
            input.addEventListener('input', calculatePrice);
        });

        function calculatePrice() {
            const quantity = parseInt(this.value) || 0;
            const rate = parseFloat(serviceRateInput.value) || 0;
            const total = (quantity * rate).toFixed(2);
            priceDisplay.textContent = `$${total}`;
        }
    }

    // =====================================================
    // Order Status Checker
    // =====================================================

    const statusCheckBtn = document.getElementById('checkStatusBtn');
    const statusInput = document.getElementById('orderIdInput');
    const statusResult = document.getElementById('statusResult');

    if (statusCheckBtn && statusInput) {
        statusCheckBtn.addEventListener('click', async () => {
            const orderId = statusInput.value.trim();

            if (!orderId) {
                showNotification('Please enter an order ID', 'error');
                return;
            }

            try {
                const result = await apiFetch(`/api/status.php?id=${orderId}`);

                if (result.success) {
                    const data = result.data;
                    statusResult.innerHTML = `
                        <div class="status-info">
                            <p><strong>Order ID:</strong> ${data.order_id}</p>
                            <p><strong>Service:</strong> ${data.service_name}</p>
                            <p><strong>Status:</strong> <span class="status-badge status-${data.status}">${data.status}</span></p>
                            <p><strong>Quantity:</strong> ${data.quantity.delivered} / ${data.quantity.requested}</p>
                            <p><strong>Created:</strong> ${data.created_at}</p>
                        </div>
                    `;
                }
            } catch (error) {
                showNotification(error.message, 'error');
            }
        });
    }

    // =====================================================
    // Balance Refresh
    // =====================================================

    const refreshBalanceBtn = document.getElementById('refreshBalanceBtn');
    const balanceDisplay = document.getElementById('balanceDisplay');

    if (refreshBalanceBtn) {
        refreshBalanceBtn.addEventListener('click', async () => {
            try {
                const result = await apiFetch('/api/balance.php');
                if (result.success) {
                    updateBalance(result.data.balance.amount);
                }
            } catch (error) {
                console.error('Failed to refresh balance:', error);
            }
        });
    }

    /**
     * Update balance display
     */
    function updateBalance(amount) {
        if (balanceDisplay) {
            balanceDisplay.textContent = `$${parseFloat(amount).toFixed(2)}`;
        }
    }

    // =====================================================
    // Notifications / Toasts
    // =====================================================

    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            animation: slideIn 0.3s ease;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Add animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);

    // =====================================================
    // Auto-sync Indicator (for cron)
    // =====================================================

    const syncIndicator = document.getElementById('syncIndicator');
    if (syncIndicator) {
        // Simulate sync status (in real implementation, this would check API)
        setInterval(() => {
            syncIndicator.classList.add('syncing');
            setTimeout(() => syncIndicator.classList.remove('syncing'), 2000);
        }, 60000);
    }

    // =====================================================
    // Service Filtering
    // =====================================================

    const platformFilters = document.querySelectorAll('.filter-tab');
    const serviceCards = document.querySelectorAll('.service-card');

    platformFilters.forEach(filter => {
        filter.addEventListener('click', (e) => {
            e.preventDefault();
            const platform = filter.dataset.platform || filter.getAttribute('href').split('=')[1] || 'all';

            // Update active state
            platformFilters.forEach(f => f.classList.remove('active'));
            filter.classList.add('active');

            // Filter cards
            serviceCards.forEach(card => {
                if (platform === 'all' || card.dataset.platform === platform) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // =====================================================
    // Search Functionality
    // =====================================================

    const searchInput = document.getElementById('serviceSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();

            serviceCards.forEach(card => {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('.service-description')?.textContent.toLowerCase() || '';

                if (name.includes(term) || description.includes(term)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // =====================================================
    // FAQ Accordion
    // =====================================================

    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const summary = item.querySelector('summary');
        const icon = summary.querySelector('.expand-icon');

        item.addEventListener('toggle', () => {
            icon.textContent = item.open ? '−' : '+';
        });
    });

    // =====================================================
    // Lazy Loading for Images
    // =====================================================

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // =====================================================
    // Form Validation
    // =====================================================

    const validateForm = (form) => {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required]');

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('error');
                isValid = false;
            } else {
                input.classList.remove('error');
            }
        });

        return isValid;
    };

    // Add error class styling
    const errorStyle = document.createElement('style');
    errorStyle.textContent = `
        input.error { border-color: #ef4444 !important; }
    `;
    document.head.appendChild(errorStyle);

    // =====================================================
    // Initialize
    // =====================================================

    document.addEventListener('DOMContentLoaded', () => {
        console.log('SMM Panel initialized');

        // Set current year in footer
        const yearEl = document.getElementById('currentYear');
        if (yearEl) {
            yearEl.textContent = new Date().getFullYear();
        }
    });

})();