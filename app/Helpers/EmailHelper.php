<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * File: Helpers/EmailHelper.php
 * Deskripsi: Helper untuk mengirim email menggunakan PHPMailer
 *
 * Fungsi utama:
 * - sendAcceptanceEmail(): Kirim email penerimaan (status: Diterima)
 * - sendRejectionEmail(): Kirim email penolakan (status: Ditolak)
 * - sendMail(): Method internal untuk send email via SMTP
 *
 * Konfigurasi Email:
 * - MAIL_HOST: SMTP host (smtp.gmail.com)
 * - MAIL_PORT: SMTP port (587 untuk TLS)
 * - MAIL_USERNAME: Email pengirim
 * - MAIL_PASSWORD: App password (bukan password email biasa)
 * - MAIL_ENCRYPTION: tls atau ssl
 * - MAIL_FROM_ADDRESS: Email pengirim
 * - MAIL_FROM_NAME: Nama pengirim
 *
 * Catatan untuk Gmail:
 * 1. Aktifkan 2-Factor Authentication di Google Account
 * 2. Generate App Password di https://myaccount.google.com/apppasswords
 * 3. Gunakan App Password sebagai MAIL_PASSWORD di .env
 */

class EmailHelper
{
    /**
     * Kirim email penerimaan kepada mahasiswa yang diterima
     *
     * @param string $toEmail - Email penerima
     * @param string $namaMahasiswa - Nama mahasiswa
     * @param string $judulRekrutmen - Judul rekrutmen
     * @return array - ['success' => bool, 'message' => string]
     */
    public static function sendAcceptanceEmail($toEmail, $namaMahasiswa, $judulRekrutmen)
    {
        $subject = "Selamat! Anda Diterima sebagai Asisten Laboratorium";

        $body = self::getAcceptanceEmailTemplate($namaMahasiswa, $judulRekrutmen);

        return self::sendMail($toEmail, $namaMahasiswa, $subject, $body);
    }

    /**
     * Kirim email penolakan kepada mahasiswa yang ditolak
     *
     * @param string $toEmail - Email penerima
     * @param string $namaMahasiswa - Nama mahasiswa
     * @param string $judulRekrutmen - Judul rekrutmen
     * @param string|null $deskripsi - Feedback/alasan penolakan (HTML from Quill)
     * @return array - ['success' => bool, 'message' => string]
     */
    public static function sendRejectionEmail($toEmail, $namaMahasiswa, $judulRekrutmen, $deskripsi = null)
    {
        $subject = "Informasi Hasil Seleksi Asisten Laboratorium";

        $body = self::getRejectionEmailTemplate($namaMahasiswa, $judulRekrutmen, $deskripsi);

        return self::sendMail($toEmail, $namaMahasiswa, $subject, $body);
    }

    /**
     * Internal method untuk mengirim email via SMTP
     *
     * @param string $toEmail - Email penerima
     * @param string $toName - Nama penerima
     * @param string $subject - Subject email
     * @param string $body - HTML body email
     * @return array - ['success' => bool, 'message' => string]
     */
    private static function sendMail($toEmail, $toName, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
            $mail->Port       = $_ENV['MAIL_PORT'] ?? 587;

            // Recipients
            $mail->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@appliedinformatics.com',
                $_ENV['MAIL_FROM_NAME'] ?? 'Applied Informatics Laboratory'
            );
            $mail->addAddress($toEmail, $toName);
            $mail->addReplyTo($_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@appliedinformatics.com', 'No Reply');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body); // Plain text version

            $mail->send();

            return [
                'success' => true,
                'message' => 'Email berhasil dikirim ke ' . $toEmail
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $mail->ErrorInfo
            ];
        }
    }

    /**
     * Template HTML untuk email penerimaan (fancy & profesional)
     *
     * @param string $namaMahasiswa
     * @param string $judulRekrutmen
     * @return string - HTML template
     */
    private static function getAcceptanceEmailTemplate($namaMahasiswa, $judulRekrutmen)
    {
        return '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: #01a3a7;
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .icon-success {
            font-size: 64px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 24px;
            line-height: 1.8;
        }
        .highlight-box {
            background: #f7fafc;
            border-left: 4px solid #01a3a7;
            padding: 20px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .highlight-box h3 {
            margin: 0 0 12px 0;
            color: #2d3748;
            font-size: 18px;
        }
        .highlight-box p {
            margin: 0;
            color: #4a5568;
        }
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon-success">🎉</div>
            <h1>Selamat! Anda Diterima</h1>
        </div>

        <div class="content">
            <p class="greeting">Halo, ' . htmlspecialchars($namaMahasiswa) . '</p>

            <p class="message">
                Kami dengan senang hati mengumumkan bahwa Anda telah <strong>diterima</strong> sebagai
                <strong>Asisten Laboratorium</strong> di Applied Informatics Laboratory!
            </p>

            <div class="highlight-box">
                <h3>📋 Detail Rekrutmen</h3>
                <p><strong>Posisi:</strong> ' . htmlspecialchars($judulRekrutmen) . '</p>
            </div>

            <p class="message">
                Selamat datang di keluarga besar Applied Informatics! Kami sangat menantikan kontribusi
                dan dedikasi Anda dalam memajukan laboratorium kami.
            </p>

            <p class="message">
                Tim kami akan segera menghubungi Anda melalui email atau WhatsApp untuk informasi lebih
                lanjut mengenai jadwal orientasi dan tugas-tugas Anda.
            </p>

            <p class="signature">
                Salam hangat,<br>
                <strong>Tim Applied Informatics Laboratory</strong>
            </p>
        </div>

        <div class="footer">
            <p><strong>Applied Informatics Laboratory</strong></p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p style="margin-top: 16px; font-size: 12px; color: #a0aec0;">
                © ' . date('Y') . ' Applied Informatics Laboratory. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
        ';
    }

    /**
     * Template HTML untuk email penolakan (sopan & profesional)
     *
     * @param string $namaMahasiswa
     * @param string $judulRekrutmen
     * @param string|null $deskripsi - Feedback/alasan penolakan (HTML from Quill)
     * @return string - HTML template
     */
    private static function getRejectionEmailTemplate($namaMahasiswa, $judulRekrutmen, $deskripsi = null)
    {
        // Build feedback section if deskripsi exists
        $feedbackSection = '';
        if (!empty($deskripsi)) {
            $feedbackSection = '
            <div class="feedback-box">
                <h3>📝 Catatan dari Tim Penyeleksi</h3>
                <div class="feedback-content">' . $deskripsi . '</div>
            </div>';
        }

        return '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 24px;
            line-height: 1.8;
        }
        .info-box {
            background: #f7fafc;
            border-left: 4px solid #4a5568;
            padding: 20px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #4a5568;
        }
        .feedback-box {
            background: #fff5f5;
            border-left: 4px solid #c53030;
            padding: 20px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .feedback-box h3 {
            margin: 0 0 12px 0;
            color: #c53030;
            font-size: 16px;
        }
        .feedback-content {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.7;
        }
        .feedback-content strong, .feedback-content b {
            font-weight: 700;
        }
        .feedback-content em, .feedback-content i {
            font-style: italic;
        }
        .feedback-content u {
            text-decoration: underline;
        }
        .feedback-content ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .feedback-content li {
            margin-bottom: 6px;
        }
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Informasi Hasil Seleksi</h1>
        </div>

        <div class="content">
            <p class="greeting">Halo, ' . htmlspecialchars($namaMahasiswa) . '</p>

            <p class="message">
                Terima kasih atas minat dan partisipasi Anda dalam proses seleksi
                <strong>' . htmlspecialchars($judulRekrutmen) . '</strong>
                di Applied Informatics Laboratory.
            </p>

            <p class="message">
                Setelah melalui proses seleksi yang ketat, dengan berat hati kami informasikan bahwa
                Anda <strong>belum dapat kami terima</strong> pada kesempatan kali ini.
            </p>

            ' . $feedbackSection . '

            <div class="info-box">
                <p>
                    Keputusan ini bukan merupakan penilaian terhadap kemampuan atau potensi Anda.
                    Kompetisi sangat ketat dan kami harus membuat keputusan sulit berdasarkan
                    berbagai pertimbangan.
                </p>
            </div>

            <p class="message">
                Kami sangat menghargai waktu dan usaha yang telah Anda curahkan dalam proses seleksi ini.
                Kami mendorong Anda untuk terus mengembangkan kemampuan dan mencoba lagi di kesempatan
                rekrutmen berikutnya.
            </p>

            <p class="message">
                Semoga kesuksesan selalu menyertai Anda di masa depan!
            </p>

            <p class="signature">
                Salam hormat,<br>
                <strong>Tim Applied Informatics Laboratory</strong>
            </p>
        </div>

        <div class="footer">
            <p><strong>Applied Informatics Laboratory</strong></p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p style="margin-top: 16px; font-size: 12px; color: #a0aec0;">
                © ' . date('Y') . ' Applied Informatics Laboratory. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
        ';
    }

    /**
     * Kirim email balasan ke pengirim pesan contact us
     *
     * @param string $toEmail - Email penerima
     * @param string $namaPengirim - Nama pengirim pesan
     * @param string $balasanHTML - Balasan dari admin (HTML from Quill)
     * @param string $pesanAsli - Pesan asli dari pengirim (untuk context)
     * @return array - ['success' => bool, 'message' => string]
     */
    public static function sendReplyEmail($toEmail, $namaPengirim, $balasanHTML, $pesanAsli)
    {
        $subject = "Balasan dari Applied Informatics Laboratory";

        $body = self::getReplyEmailTemplate($namaPengirim, $balasanHTML, $pesanAsli);

        return self::sendMail($toEmail, $namaPengirim, $subject, $body);
    }

    /**
     * Template HTML untuk email balasan contact us
     *
     * @param string $namaPengirim
     * @param string $balasanHTML - HTML dari Quill editor
     * @param string $pesanAsli - Pesan asli untuk context
     * @return string - HTML template
     */

    /**
     * Template HTML untuk email balasan contact us
     * FIXED: Removed emoji unicode, improved styling
     */
    private static function getReplyEmailTemplate($namaPengirim, $balasanHTML, $pesanAsli)
    {
        return '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #01a3a7 0%, #0891b2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 24px;
            line-height: 1.8;
        }
        .reply-box {
            background: #f7fafc;
            border-left: 4px solid #01a3a7;
            padding: 20px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .reply-box h3 {
            margin: 0 0 12px 0;
            color: #2d3748;
            font-size: 16px;
        }
        .reply-content {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.7;
        }
        .reply-content p {
            margin: 0 0 12px 0;
        }
        .reply-content strong, .reply-content b {
            font-weight: 700;
        }
        .reply-content em, .reply-content i {
            font-style: italic;
        }
        .reply-content u {
            text-decoration: underline;
        }
        .reply-content h1, .reply-content h2, .reply-content h3 {
            margin: 16px 0 12px 0;
            color: #2d3748;
        }
        .reply-content ol, .reply-content ul {
            margin: 10px 0;
            padding-left: 24px;
        }
        .reply-content li {
            margin-bottom: 6px;
        }
        .reply-content a {
            color: #01a3a7;
            text-decoration: none;
        }
        .original-message-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .original-message-box h4 {
            margin: 0 0 10px 0;
            color: #78350f;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .original-message-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
            line-height: 1.6;
        }
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #718096;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-style: italic;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">💬</div>
            <h1>Balasan Pesan Anda</h1>
        </div>

        <div class="content">
            <p class="greeting">Halo, ' . htmlspecialchars($namaPengirim) . '</p>

            <p class="message">
                Terima kasih telah menghubungi Applied Informatics Laboratory. 
                Berikut adalah balasan dari tim kami:
            </p>

            <div class="reply-box">
                <h3>📧 Balasan dari Tim Kami</h3>
                <div class="reply-content">' . $balasanHTML . '</div>
            </div>

            <div class="original-message-box">
                <h4>Pesan Anda:</h4>
                <p>' . nl2br(htmlspecialchars($pesanAsli)) . '</p>
            </div>

            <p class="message">
                Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami kembali.
            </p>

            <p class="signature">
                Salam hormat,<br>
                <strong>Tim Applied Informatics Laboratory</strong>
            </p>
        </div>

        <div class="footer">
            <p><strong>Applied Informatics Laboratory</strong></p>
            <p>Email ini adalah balasan dari pesan Anda sebelumnya.</p>
            <p style="margin-top: 16px; font-size: 12px; color: #a0aec0;">
                © ' . date('Y') . ' Applied Informatics Laboratory. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
        ';
    }
}