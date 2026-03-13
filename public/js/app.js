// FISHERIES - Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initMobileMenu();
    initDropdowns();
    initHeroSlider();
    initScrollAnimations();
    initSmoothScroll();
    initCart();
    initFilters();
    initCharts();
    initModals();
    initTooltips();
    initLazyLoading();
});

// Mobile Menu Toggle
function initMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            const icon = mobileMenuBtn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        });
    }
}

// Dropdown Menus
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

// Hero Slider
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
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
    
    // Auto-advance slides
    setInterval(nextSlide, 5000);
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
    
    // Show first slide
    showSlide(0);
}

// Scroll Animations
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    animatedElements.forEach(el => observer.observe(el));
}

// Smooth Scroll
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Shopping Cart
function initCart() {
    let cart = JSON.parse(localStorage.getItem('fisheries_cart')) || [];
    
    window.addToCart = function(productId, name, price, quantity = 1) {
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({ id: productId, name, price, quantity });
        }
        
        localStorage.setItem('fisheries_cart', JSON.stringify(cart));
        updateCartCount();
        showToast(`${name} ditambahkan ke keranjang`);
    };
    
    window.removeFromCart = function(productId) {
        cart = cart.filter(item => item.id !== productId);
        localStorage.setItem('fisheries_cart', JSON.stringify(cart));
        updateCartCount();
        renderCart();
    };
    
    function updateCartCount() {
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        const badges = document.querySelectorAll('.cart-count');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        });
    }
    
    function renderCart() {
        const cartContainer = document.getElementById('cart-items');
        if (!cartContainer) return;
        
        if (cart.length === 0) {
            cartContainer.innerHTML = '<p class="text-gray-500 text-center py-8">Keranjang kosong</p>';
            return;
        }
        
        cartContainer.innerHTML = cart.map(item => `
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h4 class="font-medium">${item.name}</h4>
                    <p class="text-sm text-gray-500">${item.quantity} x Rp ${item.price.toLocaleString()}</p>
                </div>
                <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `).join('');
        
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const totalElement = document.getElementById('cart-total');
        if (totalElement) {
            totalElement.textContent = `Rp ${total.toLocaleString()}`;
        }
    }
    
    // Initialize
    updateCartCount();
    renderCart();
}

// Product Filters
function initFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active state
            filterButtons.forEach(b => b.classList.remove('active', 'bg-blue-600', 'text-white'));
            this.classList.add('active', 'bg-blue-600', 'text-white');
            
            // Filter products
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
    
    // Price range filter
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = `Rp ${parseInt(this.value).toLocaleString()}`;
        });
    }
}

// Charts (using Chart.js)
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
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }, {
                label: 'Tambak (Ha)',
                data: [1200, 800, 1500, 600, 450, 1100, 350],
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Perikanan Kalimantan Timur 2024'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Modal System
function initModals() {
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };
    
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };
    
    // Close on backdrop click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(modal => {
                modal.classList.remove('active');
            });
            document.body.style.overflow = '';
        }
    });
}

// Tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(el => {
        el.classList.add('tooltip');
    });
}

// Lazy Loading Images
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

// Toast Notifications
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'border-l-4 border-red-500' : 'border-l-4 border-green-500'}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-check-circle text-green-500'} mr-3"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Form Validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('border-red-500');
            
            // Add error message
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
            if (error && error.classList.contains('error-message')) {
                error.remove();
            }
        }
    });
    
    return isValid;
}

// Search Functionality
function initSearch() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            if (searchResults) searchResults.classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
}

function performSearch(query) {
    // Simulated search - in production, this would be an API call
    console.log('Searching for:', query);
}

// Print Member Card
function printMemberCard() {
    window.print();
}

// Copy to Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Berhasil disalin ke clipboard');
    }).catch(() => {
        showToast('Gagal menyalin', 'error');
    });
}

// Location Tabs (for penyewaan kapal)
function initLocationTabs() {
    const tabButtons = document.querySelectorAll('.location-tab');
    const tabContents = document.querySelectorAll('.location-content');
    
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const location = this.dataset.location;
            
            // Update active tab
            tabButtons.forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-700');
            });
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('bg-blue-600', 'text-white');
            
            // Show content
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

// Initialize location tabs if present
document.addEventListener('DOMContentLoaded', initLocationTabs);

// Export functions for global access
window.showToast = showToast;
window.printMemberCard = printMemberCard;
window.copyToClipboard = copyToClipboard;
