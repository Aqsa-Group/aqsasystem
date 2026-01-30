document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('loader');
    const mainContent = document.getElementById('mainContent');
    const progressBar = document.querySelector('.progress');

    // مدیریت منوی موبایل
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');
    
    mobileMenuBtn.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        mobileOverlay.classList.toggle('open');
    });
    
    mobileOverlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        mobileOverlay.classList.remove('open');
    });

    // مدیریت پروفایل در موبایل
    const profileBtnMobile = document.getElementById('profileBtnMobile');
    if (profileBtnMobile) {
        profileBtnMobile.addEventListener('click', () => {
            window.location.href = "{{ route('sarafi.users') }}";
        });
    }

    // مدیریت پروفایل در دسکتاپ
    const profileBtnDesktop = document.getElementById('profileBtnDesktop');
    const profileDropdownDesktop = document.getElementById('profileDropdownDesktop');
    if (profileBtnDesktop && profileDropdownDesktop) {
        profileBtnDesktop.addEventListener('click', () => {
            profileDropdownDesktop.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!profileBtnDesktop.contains(event.target) && !profileDropdownDesktop.contains(event.target)) {
                profileDropdownDesktop.classList.add('hidden');
            }
        });
    }

    // محتوا را ابتدا مخفی کن
    mainContent.style.display = 'none';

    let progress = 0;
    let fakeProgressInterval;

    function startFakeProgress() {
        fakeProgressInterval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        },10);
    }

    startFakeProgress();

    window.addEventListener('load', function() {
        clearInterval(fakeProgressInterval);
        progress = 100;
        progressBar.style.width = progress + '%';

        setTimeout(() => {
            loader.classList.add('loader-complete');
            mainContent.style.display = 'block';
            mainContent.classList.add('content-loaded');

            setTimeout(() => {
                loader.style.display = 'none';
            }, 400);
        }, 600);
    });

    // مدیریت کلیک روی لینک‌ها
    const navLinks = document.querySelectorAll('.nav-link, .locale-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('#')) {
                e.preventDefault();
                loader.style.display = 'flex';
                loader.classList.remove('loader-complete');
                setTimeout(() => window.location.href = href, 50);
            }
        });
    });

    // مدیریت dropdown زبان برای دسکتاپ
    const btn = document.getElementById('dropdownButton');
    const menu = document.getElementById('dropdownMenu');
    if (btn && menu) {
        btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        document.addEventListener('click', e => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }

    // مدیریت dropdown زبان برای موبایل
    const btnMobile = document.getElementById('dropdownButtonMobile');
    const menuMobile = document.getElementById('dropdownMenuMobile');
    if (btnMobile && menuMobile) {
        btnMobile.addEventListener('click', () => menuMobile.classList.toggle('hidden'));
        document.addEventListener('click', e => {
            if (!btnMobile.contains(e.target) && !menuMobile.contains(e.target)) {
                menuMobile.classList.add('hidden');
            }
        });
    }
});

// مدیریت دارک مود
const darkModeToggle = document.getElementById('darkModeToggle');
const sunIcon = document.getElementById('sunIcon');
const moonIcon = document.getElementById('moonIcon');
const toggleCircle = document.getElementById('toggleCircle');

const darkModeToggleMobile = document.getElementById('darkModeToggleMobile');
const sunIconMobile = document.getElementById('sunIconMobile');
const moonIconMobile = document.getElementById('moonIconMobile');
const toggleCircleMobile = document.getElementById('toggleCircleMobile');

const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
const html = document.documentElement;

const currentTheme = localStorage.getItem('theme');
if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
    html.classList.add('dark');
    if (darkModeToggle) darkModeToggle.checked = true;
    if (darkModeToggleMobile) darkModeToggleMobile.checked = true;
    if (sunIcon) sunIcon.classList.add('hidden');
    if (sunIconMobile) sunIconMobile.classList.add('hidden');
    if (moonIcon) moonIcon.classList.remove('hidden');
    if (moonIconMobile) moonIconMobile.classList.remove('hidden');
    if (toggleCircle) toggleCircle.classList.add('move-dark');
    if (toggleCircleMobile) toggleCircleMobile.classList.add('move-dark');
}

if (darkModeToggle) {
    darkModeToggle.addEventListener('change', function() {
        updateDarkMode(this.checked);
    });
}

if (darkModeToggleMobile) {
    darkModeToggleMobile.addEventListener('change', function() {
        updateDarkMode(this.checked);
    });
}

function updateDarkMode(isDark) {
    if (isDark) {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        if (sunIcon) sunIcon.classList.add('hidden');
        if (sunIconMobile) sunIconMobile.classList.add('hidden');
        if (moonIcon) moonIcon.classList.remove('hidden');
        if (moonIconMobile) moonIconMobile.classList.remove('hidden');
        if (toggleCircle) toggleCircle.classList.add('move-dark');
        if (toggleCircleMobile) toggleCircleMobile.classList.add('move-dark');
        if (darkModeToggle) darkModeToggle.checked = true;
        if (darkModeToggleMobile) darkModeToggleMobile.checked = true;
    } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        if (sunIcon) sunIcon.classList.remove('hidden');
        if (sunIconMobile) sunIconMobile.classList.remove('hidden');
        if (moonIcon) moonIcon.classList.add('hidden');
        if (moonIconMobile) moonIconMobile.classList.add('hidden');
        if (toggleCircle) toggleCircle.classList.remove('move-dark');
        if (toggleCircleMobile) toggleCircleMobile.classList.remove('move-dark');
        if (darkModeToggle) darkModeToggle.checked = false;
        if (darkModeToggleMobile) darkModeToggleMobile.checked = false;
    }
}

// تابع customerSearch
function customerSearch() {
    return {
        searchQuery: '',
        results: [],
        showResults: false,
        isLoading: false,
        selectedCustomer: null,
        showConfirmModal: false,
        isLinking: false,
        
        init() {
            console.log('Customer search initialized');
        },
        
        async performSearch() {
            if (this.searchQuery.length < 2) {
                this.showResults = false;
                this.results = [];
                return;
            }
            
            this.isLoading = true;
            
            try {
                const response = await fetch(`{{ route('api.search-customers') }}?q=${encodeURIComponent(this.searchQuery)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.error) {
                    console.error('Search error:', data.error);
                    return;
                }
                
                if (data.customers && data.customers.length > 0) {
                    this.results = data.customers;
                    this.showResults = true;
                } else {
                    this.results = [];
                    this.showResults = false;
                }
                
            } catch (error) {
                console.error('Search error:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        closeResults() {
            this.showResults = false;
        },
        
        getImageUrl(imagePath) {
            return imagePath ? `/storage/${imagePath}` : '';
        },
        
        getFirstLetter(name) {
            return name ? name.charAt(0).toUpperCase() : '?';
        },
        
        handleCustomerClick(customer) {
            if (customer.is_mine) {
                // اگر مشتری مال خودتان است، به صفحه مشتری بروید
                window.location.href = `{{ route('sarafi.customer-table') }}?customer=${customer.id}`;
            } else {
                // در غیر این صورت، مودال لینک را نشان دهید
                this.selectedCustomer = customer;
                this.showConfirmModal = true;
                this.showResults = false;
            }
        },
        
        linkCustomer(customer) {
            this.selectedCustomer = customer;
            this.showConfirmModal = true;
            this.showResults = false;
        },
        
        async confirmLinkCustomer() {
            if (!this.selectedCustomer) return;
            
            this.isLinking = true;
            
            try {
                const response = await fetch('{{ route("api.link-customer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        customer_id: this.selectedCustomer.id 
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // نمایش پیام موفقیت
                    alert(data.message);
                    
                    // بستن مودال و پاک کردن جستجو
                    this.showConfirmModal = false;
                    this.searchQuery = '';
                    this.results = [];
                    
                    // ریدایرکت به صفحه مشتریان یا رفرش
                    setTimeout(() => {
                        window.location.href = '{{ route("sarafi.customer-table") }}';
                    }, 1000);
                    
                } else {
                    alert(data.message);
                    this.showConfirmModal = false;
                }
                
            } catch (error) {
                console.error('Link error:', error);
                alert('خطا در لینک کردن مشتری');
            } finally {
                this.isLinking = false;
            }
        }
    };
}