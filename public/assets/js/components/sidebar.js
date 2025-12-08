/**
   * Sidebar Simple - jQuery Version
   * Implementasi sederhana untuk toggle sidebar
   */

  $(document).ready(function() {

      // Initialize
      initSidebar();

      /**
       * Inisialisasi sidebar
       */
      function initSidebar() {
          // Load saved state
          loadSidebarState();

          // Setup toggle button
          setupToggle();

          // Setup collapse arrows
          setupCollapseArrows();

          // Setup mobile menu
          setupMobileMenu();

          // Initialize Feather Icons
          if (typeof feather !== 'undefined') {
              feather.replace();
          }
      }

      /**
       * Toggle sidebar collapse/expand
       */
      function setupToggle() {
          $('#toggleSidebar').on('click', function() {
              const $sidebar = $('#sidebar');
              const $mainContent = $('.main-content');

              // Toggle collapsed class
              $sidebar.toggleClass('collapsed');
              $mainContent.toggleClass('expanded');

              // Change icon
              const isCollapsed = $sidebar.hasClass('collapsed');
              const iconName = isCollapsed ? 'chevron-right' : 'chevron-left';
              $(this).find('i').attr('data-feather', iconName);

              // Re-initialize icons
              if (typeof feather !== 'undefined') {
                  feather.replace();
              }

              // Save state
              localStorage.setItem('sidebarCollapsed', isCollapsed);
          });
      }

      /**
       * Setup collapse arrow animations
       */
      function setupCollapseArrows() {
          // Saat collapse di-show
          $('.collapse').on('show.bs.collapse', function() {
              const $toggle = $('[href="#' + this.id + '"]');
              $toggle.addClass('expanded');

              // Re-initialize icons setelah animasi
              setTimeout(function() {
                  if (typeof feather !== 'undefined') {
                      feather.replace();
                  }
              }, 50);
          });

          // Saat collapse di-hide
          $('.collapse').on('hide.bs.collapse', function() {
              const $toggle = $('[href="#' + this.id + '"]');
              $toggle.removeClass('expanded');

              // Re-initialize icons setelah animasi
              setTimeout(function() {
                  if (typeof feather !== 'undefined') {
                      feather.replace();
                  }
              }, 50);
          });
      }

      /**
       * Setup mobile menu
       */
      function setupMobileMenu() {
          // Open sidebar on mobile when toggle button clicked
          $('#mobileMenuToggle').on('click', function() {
              toggleMobileSidebar();
          });

          // Close sidebar on overlay click
          $('#sidebarOverlay').on('click', function() {
              closeMobileSidebar();
          });

          // Close on ESC key
          $(document).on('keydown', function(e) {
              if (e.key === 'Escape' || e.keyCode === 27) {
                  if ($('#sidebar').hasClass('show')) {
                      closeMobileSidebar();
                  }
              }
          });

          // Close sidebar when clicking a link on mobile
          if ($(window).width() <= 992) {
              $('.sidebar .nav-link').on('click', function(e) {
                  // Don't close if it's a parent menu (has submenu)
                  if (!$(this).hasClass('parent-menu')) {
                      setTimeout(function() {
                          closeMobileSidebar();
                      }, 200);
                  }
              });
          }

          // Handle window resize
          $(window).on('resize', function() {
              if ($(window).width() > 992) {
                  // Desktop mode - ensure sidebar is visible and overlay is hidden
                  $('#sidebar').removeClass('show');
                  $('#sidebarOverlay').removeClass('show');
              }
          });
      }

      /**
       * Toggle mobile sidebar
       */
      function toggleMobileSidebar() {
          const $sidebar = $('#sidebar');
          const $overlay = $('#sidebarOverlay');

          if ($sidebar.hasClass('show')) {
              closeMobileSidebar();
          } else {
              openMobileSidebar();
          }
      }

      /**
       * Open mobile sidebar
       */
      function openMobileSidebar() {
          $('#sidebar').addClass('show');
          $('#sidebarOverlay').addClass('show');

          // Prevent body scroll when sidebar is open
          $('body').css('overflow', 'hidden');

          // Update hamburger icon to X
          updateMobileMenuIcon(true);
      }

      /**
       * Close mobile sidebar
       */
      function closeMobileSidebar() {
          $('#sidebar').removeClass('show');
          $('#sidebarOverlay').removeClass('show');

          // Re-enable body scroll
          $('body').css('overflow', '');

          // Update icon back to hamburger
          updateMobileMenuIcon(false);
      }

      /**
       * Update mobile menu toggle icon
       */
      function updateMobileMenuIcon(isOpen) {
          const iconName = isOpen ? 'x' : 'menu';
          $('#mobileMenuToggle i').attr('data-feather', iconName);

          if (typeof feather !== 'undefined') {
              feather.replace();
          }
      }

      /**
       * Load sidebar state dari localStorage
       */
      function loadSidebarState() {
          const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

          if (isCollapsed) {
              $('#sidebar').addClass('collapsed');
              $('.main-content').addClass('expanded');

              // Update icon
              $('#toggleSidebar i').attr('data-feather', 'chevron-right');

              if (typeof feather !== 'undefined') {
                  feather.replace();
              }
          }
      }

      /**
       * Public API
       */
      window.Sidebar = {
          toggle: function() {
              $('#toggleSidebar').trigger('click');
          },

          collapse: function() {
              if (!$('#sidebar').hasClass('collapsed')) {
                  $('#toggleSidebar').trigger('click');
              }
          },

          expand: function() {
              if ($('#sidebar').hasClass('collapsed')) {
                  $('#toggleSidebar').trigger('click');
              }
          },

          showOnMobile: function() {
              openMobileSidebar();
          },

          hideOnMobile: function() {
              closeMobileSidebar();
          },

          toggleMobile: function() {
              toggleMobileSidebar();
          }
      };

  });

