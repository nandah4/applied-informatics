<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Applied Informatics Laboratory</title>

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/components/sukses_pendaftaran.css') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>

<body>

    <div class="success-container">
        <div class="success-card">
            <!-- Success Icon -->
            <div class="success-icon-wrapper">
                <i class="bi bi-check-circle-fill success-icon"></i>
            </div>

            <!-- Title & Message -->
            <h1 class="success-title">Pendaftaran Berhasil!</h1>
            <p class="success-message">
                <?= $_SESSION['success_message'] ?? 'Terima kasih telah mendaftar sebagai asisten lab Applied Informatics. Data Anda telah kami terima dan akan segera diproses oleh tim kami.' ?>
            </p>

            <!-- Info Boxes -->
            <div class="info-boxes">
                <div class="info-box">
                    <div class="info-box-header">
                        <div class="info-box-icon">
                            <i class="bi bi-envelope-check"></i>
                        </div>
                        <h6 class="info-box-title">Email Konfirmasi</h6>
                    </div>
                    <p class="info-box-text">
                        Kami telah mengirimkan email konfirmasi ke alamat email yang Anda daftarkan. Silakan cek inbox atau folder spam Anda.
                    </p>
                </div>

                <div class="info-box">
                    <div class="info-box-header">
                        <div class="info-box-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h6 class="info-box-title">Proses Seleksi</h6>
                    </div>
                    <p class="info-box-text">
                        Tim kami akan meninjau aplikasi Anda dalam 3-5 hari kerja. Informasi tahap selanjutnya akan dikirimkan melalui email dan WhatsApp.
                    </p>
                </div>

                <div class="info-box">
                    <div class="info-box-header">
                        <div class="info-box-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <h6 class="info-box-title">Penting</h6>
                    </div>
                    <p class="info-box-text">
                        Pastikan email dan nomor WhatsApp Anda aktif. Kami akan menghubungi Anda untuk informasi jadwal interview atau tes berikutnya.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="<?= base_url('/') ?>" class="btn-secondary">
                    <i class="bi bi-house"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="<?= base_url('rekrutment') ?>" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    <span>Lihat Rekrutmen Lain</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Confetti Elements -->
    <script>
        // Create confetti elements
        function createConfetti() {
            const colors = [
                'var(--color-primary-500)',
                'var(--color-primary-600)',
                'var(--color-tertiary-500)',
                'var(--color-secondary-500)',
            ];

            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                document.body.appendChild(confetti);

                // Remove confetti after animation
                setTimeout(() => confetti.remove(), 5000);
            }
        }

        // Trigger confetti on page load
        window.addEventListener('load', createConfetti);
    </script>

    <!-- jQuery -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <?php
    // Clear success message setelah ditampilkan
    unset($_SESSION['success_message']);
    ?>

</body>

</html>