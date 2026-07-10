// FISHERIES - Main JavaScript File (Enhanced Modern UI)

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initScrollProgressBar();
    initNavbarScrollTransform();
    initMobileMenu();
    initDropdowns();
    initHeroSlider();
    initScrollReveal();
    initSmoothScroll();
    initCart();
    initFilters();
    initCharts();
    initModals();
    initTooltips();
    initLazyLoading();
    initRippleEffect();
    initCountUp();
    initHeroParallax();
});

// ─────────────────────────────────────────────
// SCROLL PROGRESS BAR
// ─────────────────────────────────────────────
function initScrollProgressBar() {
    const bar = document.getElementById('scroll-progress-bar');
    if (!bar) return;
    window.addEventListener('scroll', () => {
        const scrollTop    = window.scrollY;
        const docHeight    = document.documentElement.scrollHeight - window.innerHeight;
        const progress     = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        bar.style.width    = progress + '%';
    }, { passive: true });
}

// ─────────────────────────────────────────────
// NAVBAR SCROLL TRANSFORM (solid color scrolling shadow adjustments)
// ─────────────────────────────────────────────
function initNavbarScrollTransform() {
    const nav = document.querySelector('nav');
    if (!nav) return;
    
    const onScroll = () => {
        if (window.scrollY > 60) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // initial check
}

// ─────────────────────────────────────────────
// HERO PARALLAX
// ─────────────────────────────────────────────
function initHeroParallax() {
    const heroSlider = document.getElementById('hero-slider');
    if (!heroSlider) return;
    window.addEventListener('scroll', () => {
        const y = window.scrollY;
        // Clamp parallax to hero height
        const maxY = heroSlider.parentElement ? heroSlider.parentElement.offsetHeight : 500;
        if (y <= maxY) {
            heroSlider.style.transform = `translateY(${y * 0.35}px)`;
        }
    }, { passive: true });
}

// ─────────────────────────────────────────────
// MOBILE MENU TOGGLE
// ─────────────────────────────────────────────
function initMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu    = document.getElementById('mobile-menu');
    const menuIcon      = document.getElementById('menu-icon');

    if (!mobileMenuBtn || !mobileMenu) return;

    mobileMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden');
        if (menuIcon) {
            menuIcon.classList.toggle('fa-bars',  !isHidden);
            menuIcon.classList.toggle('fa-times',  isHidden);
        }
        mobileMenuBtn.classList.toggle('bg-blue-50',   !isHidden);
        mobileMenuBtn.classList.toggle('text-blue-600', !isHidden);
        mobileMenuBtn.classList.toggle('shadow-sm',    !isHidden);
        mobileMenuBtn.classList.toggle('bg-blue-100',   isHidden);
        mobileMenuBtn.classList.toggle('text-blue-700', isHidden);
        mobileMenuBtn.classList.toggle('shadow-md',     isHidden);
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target) && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            if (menuIcon) { menuIcon.classList.add('fa-bars'); menuIcon.classList.remove('fa-times'); }
            mobileMenuBtn.classList.remove('bg-blue-100', 'text-blue-700', 'shadow-md');
            mobileMenuBtn.classList.add('bg-blue-50', 'text-blue-600', 'shadow-sm');
        }
    });
}

// ─────────────────────────────────────────────
// DROPDOWN MENUS
// ─────────────────────────────────────────────
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const menu = dropdown.querySelector('.dropdown-menu');
        if (menu) {
            dropdown.addEventListener('mouseenter', () => {
                menu.classList.remove('opacity-0', 'invisible', '-translate-y-2');
                menu.classList.add('opacity-100', 'visible', 'translate-y-0');
            });
            dropdown.addEventListener('mouseleave', () => {
                menu.classList.add('opacity-0', 'invisible', '-translate-y-2');
                menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
            });
        }
    });
}

// ─────────────────────────────────────────────
// HERO SLIDER
// ─────────────────────────────────────────────
function initHeroSlider() {
    const slides     = document.querySelectorAll('.hero-slide');
    const dots       = document.querySelectorAll('.hero-dot');
    let currentSlide = 0;
    const totalSlides = slides.length;
    if (totalSlides === 0) return;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('opacity-100', 'z-10');
            slide.classList.add('opacity-0', 'z-0');
            if (dots[i]) {
                dots[i].classList.remove('bg-white', 'scale-125');
                dots[i].classList.add('bg-white/50');
            }
        });
        slides[index].classList.remove('opacity-0', 'z-0');
        slides[index].classList.add('opacity-100', 'z-10');
        if (dots[index]) {
            dots[index].classList.remove('bg-white/50');
            dots[index].classList.add('bg-white', 'scale-125');
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    setInterval(nextSlide, 5000);
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => { currentSlide = index; showSlide(currentSlide); });
    });
    showSlide(0);
}

// ─────────────────────────────────────────────
// SCROLL-REVEAL ANIMATIONS (IntersectionObserver)
// ─────────────────────────────────────────────
function initScrollReveal() {
    const selector = '.reveal, .reveal-left, .reveal-right, .reveal-scale, .animate-on-scroll, .chart-card';
    const animatedEls = document.querySelectorAll(selector);

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Legacy .animate-on-scroll support
                if (entry.target.classList.contains('animate-on-scroll')) {
                    entry.target.classList.add('animate-fade-in');
                }
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    animatedEls.forEach(el => observer.observe(el));

    // Auto-add reveal class to common sections if not already present
    autoTagRevealElements();
}

function autoTagRevealElements() {
    // Tag section headings
    document.querySelectorAll('h2:not(.reveal):not(.reveal-left):not([data-no-reveal])').forEach((el, i) => {
        if (el.closest('nav') || el.closest('footer') || el.closest('#hero-slider')) return;
        el.classList.add('reveal');
        initObserveOne(el);
    });

    // Tag article/news cards with stagger
    const articleCards = document.querySelectorAll('.grid > div:not(.reveal):not([data-no-reveal])');
    articleCards.forEach((el, i) => {
        if (el.closest('nav') || el.closest('footer')) return;
        el.classList.add('reveal');
        el.classList.add(`stagger-${Math.min(i % 6 + 1, 6)}`);
        initObserveOne(el);
    });

    // Tag product category cards
    document.querySelectorAll('.grid > a:not(.reveal)').forEach((el, i) => {
        if (el.closest('nav') || el.closest('footer')) return;
        el.classList.add('reveal', 'product-cat-card');
        el.classList.add(`stagger-${Math.min(i % 6 + 1, 6)}`);
        initObserveOne(el);
    });
}

function initObserveOne(el) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
    observer.observe(el);
}

// ─────────────────────────────────────────────
// SMOOTH SCROLL
// ─────────────────────────────────────────────
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// ─────────────────────────────────────────────
// COUNT-UP ANIMATION (statistics/numbers)
// ─────────────────────────────────────────────
function initCountUp() {
    const countEls = document.querySelectorAll('.count-up-stat, [data-count-up]');
    if (countEls.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el  = entry.target;
            const end = parseFloat(el.dataset.countUp || el.textContent.replace(/[^0-9.]/g, ''));
            if (isNaN(end)) return;
            const duration = 1800;
            const start    = performance.now();
            const prefix   = el.dataset.prefix || '';
            const suffix   = el.dataset.suffix || '';
            const decimals = (end.toString().split('.')[1] || '').length;

            function update(now) {
                const elapsed  = now - start;
                const progress = Math.min(elapsed / duration, 1);
                // Ease-out cubic
                const ease     = 1 - Math.pow(1 - progress, 3);
                const current  = end * ease;
                el.textContent = prefix + current.toLocaleString('id-ID', { maximumFractionDigits: decimals }) + suffix;
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
            observer.unobserve(el);
        });
    }, { threshold: 0.5 });

    countEls.forEach(el => observer.observe(el));
}

// ─────────────────────────────────────────────
// RIPPLE EFFECT on buttons
// ─────────────────────────────────────────────
function initRippleEffect() {
    document.querySelectorAll('.btn-animate, .ripple, button[class*="bg-blue"], a[class*="bg-blue"]').forEach(el => {
        el.classList.add('ripple');
        el.addEventListener('click', function(e) {
            const wave = document.createElement('span');
            wave.className = 'ripple-wave';
            const rect   = el.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height) * 2;
            wave.style.width  = size + 'px';
            wave.style.height = size + 'px';
            wave.style.left   = (e.clientX - rect.left - size / 2) + 'px';
            wave.style.top    = (e.clientY - rect.top  - size / 2) + 'px';
            el.appendChild(wave);
            wave.addEventListener('animationend', () => wave.remove());
        });
    });
}

// ─────────────────────────────────────────────
// SHOPPING CART
// ─────────────────────────────────────────────
function initCart() {
    // Di-disable karena aplikasi menggunakan implementasi server-side AJAX cart (CartController)
    // yang terdapat di resources/views/layouts/app.blade.php.
    // Mempertahankan fungsi ini akan menimpa window.addToCart.
}

// ─────────────────────────────────────────────
// PRODUCT FILTERS
// ─────────────────────────────────────────────
function initFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards  = document.querySelectorAll('.product-card');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            filterButtons.forEach(b => b.classList.remove('active', 'bg-blue-600', 'text-white'));
            this.classList.add('active', 'bg-blue-600', 'text-white');
            productCards.forEach(card => {
                const category = card.dataset.category;
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in');
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = `Rp ${parseInt(this.value).toLocaleString()}`;
        });
    }
}

// ─────────────────────────────────────────────
// CHARTS (Chart.js)
// ─────────────────────────────────────────────
function initCharts() {
    const chartCanvas = document.getElementById('fisheries-chart');
    if (!chartCanvas || typeof Chart === 'undefined') return;
    const ctx = chartCanvas.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Samarinda', 'Bontang', 'Balikpapan', 'Sangatta', 'Berau', 'Kukar', 'Paser'],
            datasets: [{
                label: 'Jumlah Nelayan',
                data: [500, 350, 420, 280, 250, 380, 180],
                backgroundColor: 'rgba(1, 154, 218, 0.8)',
                borderColor: 'rgba(1, 154, 218, 1)',
                borderWidth: 1,
                borderRadius: 6
            }, {
                label: 'Tambak (Ha)',
                data: [1200, 800, 1500, 600, 450, 1100, 350],
                backgroundColor: 'rgba(47, 182, 115, 0.8)',
                borderColor: 'rgba(47, 182, 115, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1200, easing: 'easeOutQuart' },
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Statistik Perikanan Kalimantan Timur 2024' }
            },
            scales: { y: { beginAtZero: true } }
        }
    });
}

// ─────────────────────────────────────────────
// MODAL SYSTEM
// ─────────────────────────────────────────────
function initModals() {
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
    };
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) { modal.classList.remove('active'); document.body.style.overflow = ''; }
    };
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) { this.classList.remove('active'); document.body.style.overflow = ''; }
        });
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(modal => modal.classList.remove('active'));
            document.body.style.overflow = '';
        }
    });
}

// ─────────────────────────────────────────────
// TOOLTIPS
// ─────────────────────────────────────────────
function initTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(el => el.classList.add('tooltip'));
}

// ─────────────────────────────────────────────
// LAZY LOADING IMAGES
// ─────────────────────────────────────────────
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    lazyImages.forEach(img => imageObserver.observe(img));
}

// ─────────────────────────────────────────────
// TOAST NOTIFICATIONS
// ─────────────────────────────────────────────
function showToast(title, message, type = 'success') {
    // New container-based toast (from layout)
    const container = document.getElementById('toast-container');
    if (container) {
        const toast     = document.createElement('div');
        const iconColor = type === 'success' ? 'text-green-500' : (type === 'error' ? 'text-red-500' : 'text-blue-500');
        const iconClass = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
        toast.className = 'bg-white rounded-xl shadow-xl flex items-start gap-3 p-4 border border-gray-100 transform transition-all duration-300 translate-y-full opacity-0 pointer-events-auto max-w-sm';
        toast.innerHTML = `
            <div class="${iconColor} text-xl shrink-0 mt-0.5"><i class="fas ${iconClass}"></i></div>
            <div class="flex-grow">
                <h4 class="font-bold text-gray-900 text-sm">${title}</h4>
                <p class="text-xs text-gray-600 mt-1 leading-relaxed">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition shrink-0 p-1">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(toast);
        requestAnimationFrame(() => { toast.classList.remove('translate-y-full', 'opacity-0'); });
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
        return;
    }

    // Fallback legacy toast
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'border-l-4 border-red-500' : 'border-l-4 border-green-500'}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-check-circle text-green-500'} mr-3"></i>
            <span>${message || title}</span>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ─────────────────────────────────────────────
// FORM VALIDATION
// ─────────────────────────────────────────────
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid  = true;
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('border-red-500');
            let error = input.nextElementSibling;
            if (!error || !error.classList.contains('error-message')) {
                error = document.createElement('p');
                error.className = 'error-message text-red-500 text-sm mt-1';
                input.parentNode.insertBefore(error, input.nextSibling);
            }
            error.textContent = 'Field ini wajib diisi';
        } else {
            input.classList.remove('border-red-500');
            const error = input.nextElementSibling;
            if (error && error.classList.contains('error-message')) error.remove();
        }
    });
    return isValid;
}

// ─────────────────────────────────────────────
// SEARCH
// ─────────────────────────────────────────────
function initSearch() {
    const searchInput   = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    if (!searchInput) return;
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        if (query.length < 2) { if (searchResults) searchResults.classList.add('hidden'); return; }
        searchTimeout = setTimeout(() => performSearch(query), 300);
    });
}
function performSearch(query) { console.log('Searching for:', query); }

// ─────────────────────────────────────────────
// PRINT MEMBER CARD
// ─────────────────────────────────────────────
function printMemberCard() { window.print(); }

// ─────────────────────────────────────────────
// COPY TO CLIPBOARD
// ─────────────────────────────────────────────
function copyToClipboard(text) {
    navigator.clipboard.writeText(text)
        .then(() => showToast('Berhasil', 'Teks berhasil disalin ke clipboard'))
        .catch(() => showToast('Gagal', 'Gagal menyalin teks', 'error'));
}

// ─────────────────────────────────────────────
// LOCATION TABS
// ─────────────────────────────────────────────
function initLocationTabs() {
    const tabButtons  = document.querySelectorAll('.location-tab');
    const tabContents = document.querySelectorAll('.location-content');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const location = this.dataset.location;
            tabButtons.forEach(b => { b.classList.remove('bg-blue-600', 'text-white'); b.classList.add('bg-gray-200', 'text-gray-700'); });
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('bg-blue-600', 'text-white');
            tabContents.forEach(content => {
                content.classList.add('hidden');
                if (content.dataset.location === location) {
                    content.classList.remove('hidden');
                    content.classList.add('animate-fade-in');
                }
            });
        });
    });
}
document.addEventListener('DOMContentLoaded', initLocationTabs);

// ─────────────────────────────────────────────
// DROPDOWN TOGGLE (existing global function)
// ─────────────────────────────────────────────
function toggleDropdown(button) {
    const dropdown = button.parentElement;
    const menu     = dropdown.querySelector('.dropdown-menu');
    const isActive = menu.classList.contains('active');
    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('active'));
    document.querySelectorAll('.dropdown-toggle').forEach(b => b.classList.remove('active'));
    if (!isActive) { menu.classList.add('active'); button.classList.add('active'); }
}

// ─────────────────────────────────────────────
// EXPORTS
// ─────────────────────────────────────────────
window.showToast       = showToast;
window.printMemberCard = printMemberCard;
window.copyToClipboard = copyToClipboard;
window.toggleDropdown  = toggleDropdown;
