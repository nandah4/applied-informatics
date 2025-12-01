/**
 * File: pages/dashboard.js
 * Deskripsi: Script untuk dashboard statistik
 *
 * Fitur:
 * - Inisialisasi Chart.js untuk visualisasi data
 * - Chart publikasi berdasarkan tipe (Doughnut)
 * - Chart trend aktivitas per bulan (Line)
 *
 * Dependencies:
 * - jQuery
 * - Chart.js 4.4.0
 */

(function () {
    'use strict';

    // ============================================================
    // CHART MODULE
    // ============================================================

    const ChartModule = {
        /**
         * Inisialisasi semua chart
         */
        init: function () {
            // Pastikan data tersedia dari PHP
            if (!window.DASHBOARD_DATA) {
                console.error('Dashboard data not available');
                return;
            }

            this.initPublikasiChart();
            this.initAktivitasChart();
        },

        /**
         * Inisialisasi chart publikasi berdasarkan tipe (Doughnut Chart)
         */
        initPublikasiChart: function () {
            const ctx = document.getElementById('publikasiChart');
            if (!ctx) return;

            const data = window.DASHBOARD_DATA.publikasiByTipe;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: 'Jumlah Publikasi',
                        data: Object.values(data),
                        backgroundColor: [
                            '#01B5B9',  // Primary - Riset
                            '#FFA500',  // Orange - Kekayaan Intelektual
                            '#10B981',  // Green - PPM
                            '#ff7d10',  // Orange Light - Publikasi
                        ],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Kita pakai custom legend di HTML
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%', // Membuat donut lebih tebal
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    }
                }
            });
        },

        /**
         * Inisialisasi chart trend aktivitas per bulan (Line Chart)
         */
        initAktivitasChart: function () {
            const ctx = document.getElementById('aktivitasChart');
            if (!ctx) return;

            const data = window.DASHBOARD_DATA.aktivitasPerBulan;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Aktivitas',
                        data: data.data,
                        borderColor: '#01B5B9',
                        backgroundColor: 'rgba(1, 181, 185, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4, // Smooth line
                        pointBackgroundColor: '#01B5B9',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#01B5B9',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            callbacks: {
                                label: function (context) {
                                    return `Aktivitas: ${context.parsed.y} kegiatan`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                },
                                color: '#6B7280'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6B7280'
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
    };

    // ============================================================
    // ANIMATION MODULE (Optional - untuk animasi number counting)
    // ============================================================

    const AnimationModule = {
        /**
         * Animasi number counting untuk stat cards
         */
        init: function () {
            this.animateStatNumbers();
        },

        /**
         * Animate number counting
         */
        animateStatNumbers: function () {
            const statValues = document.querySelectorAll('.stat-value');

            statValues.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
                const duration = 1500; // 1.5 detik
                const startTime = Date.now();
                const startValue = 0;

                const updateNumber = () => {
                    const currentTime = Date.now();
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Easing function (easeOutCubic)
                    const easeOutCubic = 1 - Math.pow(1 - progress, 3);

                    const currentValue = Math.floor(startValue + (finalValue - startValue) * easeOutCubic);
                    stat.textContent = currentValue;

                    if (progress < 1) {
                        requestAnimationFrame(updateNumber);
                    } else {
                        stat.textContent = finalValue;
                    }
                };

                // Start animation after a small delay
                setTimeout(() => {
                    updateNumber();
                }, 100);
            });
        }
    };

    // ============================================================
    // INISIALISASI
    // ============================================================

    $(document).ready(function () {
        // Initialize charts
        ChartModule.init();

        // Initialize animations
        AnimationModule.init();

        // Console log untuk debugging
        console.log('Dashboard initialized successfully');
    });

})();
