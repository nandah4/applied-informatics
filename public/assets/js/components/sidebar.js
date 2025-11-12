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
          // Open sidebar on mobile
          $('#mobileMenuBtn').on('click', function() {
              $('#sidebar').addClass('show');
              showOverlay();
          });

          // Close sidebar on overlay click
          $(document).on('click', '.sidebar-overlay', function() {
              closeMobileSidebar();
          });

          // Close on ESC key
          $(document).on('keydown', function(e) {
              if (e.key === 'Escape') {
                  closeMobileSidebar();
              }
          });
      }

      /**
       * Show overlay untuk mobile
       */
      function showOverlay() {
          if ($(window).width() <= 768) {
              // Buat overlay jika belum ada
              if ($('.sidebar-overlay').length === 0) {
                  $('body').append('<div class="sidebar-overlay"></div>');
              }

              // Show overlay dengan fade in
              setTimeout(function() {
                  $('.sidebar-overlay').addClass('show');
              }, 10);
          }
      }

      /**
       * Close mobile sidebar
       */
      function closeMobileSidebar() {
          $('#sidebar').removeClass('show');
          $('.sidebar-overlay').removeClass('show');

          // Remove overlay setelah animasi
          setTimeout(function() {
              $('.sidebar-overlay').remove();
          }, 300);
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
              $('#sidebar').addClass('show');
              showOverlay();
          },

          hideOnMobile: function() {
              closeMobileSidebar();
          }
      };

  });

