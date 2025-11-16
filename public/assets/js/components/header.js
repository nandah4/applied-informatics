/**
 * Header Component JavaScript - Minimalist
 * Applied Informatics Laboratory
 * Simple scroll effects and active state management
 */

(function() {
    'use strict';

    // ========== DOM Elements ==========
    const navbar = document.getElementById('mainHeader');
    const navLinks = document.querySelectorAll('.nav-link');
    const navbarCollapse = document.getElementById('navbarNav');

    // ========== SCROLL EFFECT ==========
    function handleScroll() {
        if (window.scrollY > 20) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }

    // Add scroll event listener
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Check initial state

    // ========== ACTIVE STATE DETECTION ==========
    function setActiveLink() {
        const currentPath = window.location.pathname;

        navLinks.forEach(link => {
            // Skip the rekrutmen button (it's styled differently)
            if (link.classList.contains('btn-primary')) {
                return;
            }

            const linkPath = new URL(link.href, window.location.origin).pathname;

            // Remove active class
            link.classList.remove('active');

            // Add active class if paths match
            if (linkPath === currentPath ||
                (currentPath !== '/' && currentPath.startsWith(linkPath) && linkPath !== '/')) {
                link.classList.add('active');
            }
        });

        // Special case for home page
        if (currentPath === '/' || currentPath === '' || currentPath === '/index.php') {
            const homeLink = Array.from(navLinks).find(link => {
                const linkPath = new URL(link.href, window.location.origin).pathname;
                return linkPath === '/' && !link.classList.contains('btn-primary');
            });
            if (homeLink) {
                homeLink.classList.add('active');
            }
        }
    }

    // Set active link on page load
    setActiveLink();

    // ========== AUTO-CLOSE MOBILE MENU ON LINK CLICK ==========
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Close mobile menu when a link is clicked
            if (window.innerWidth < 992 && navbarCollapse) {
                // Get Bootstrap Collapse instance
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                } else {
                    // If no instance exists, create one and hide it
                    const collapse = new bootstrap.Collapse(navbarCollapse, {
                        toggle: false
                    });
                    collapse.hide();
                }
            }
        });
    });

    // ========== PREVENT DROPDOWN CLOSE ON MOBILE WHEN CLICKING TOGGLE ==========
    // This ensures dropdown behavior works correctly in mobile
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                // Let Bootstrap handle dropdown in mobile
                e.stopPropagation();
            }
        });
    });

    // ========== SMOOTH SCROLL FOR ANCHOR LINKS ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '#!') return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const navbarHeight = navbar.offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight - 20;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });

                // Close mobile menu if open
                if (window.innerWidth < 992 && navbarCollapse) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }
            }
        });
    });

    // ========== CLOSE MOBILE MENU ON WINDOW RESIZE ==========
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Close mobile menu when resizing to desktop view
            if (window.innerWidth >= 992 && navbarCollapse && navbarCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        }, 250);
    });

    // ========== KEYBOARD ACCESSIBILITY ==========
    // ESC key to close mobile menu
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && navbarCollapse && navbarCollapse.classList.contains('show')) {
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
            // Return focus to toggler button
            const toggler = document.querySelector('.navbar-toggler');
            if (toggler) {
                toggler.focus();
            }
        }
    });

    // ========== INITIALIZATION COMPLETE ==========
    console.log('✅ Header component initialized successfully');

})();
