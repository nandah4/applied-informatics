<?php

function formatTanggal($tanggal, $isHours = false)
{
    if (!$tanggal) return '-';
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    $date = date_create($tanggal);

    if ($isHours) {
        return date('d F Y, H:i', strtotime($tanggal));
    }

    return date_format($date, 'd') . ' ' . $bulan[(int)date_format($date, 'n')] . ' ' . date_format($date, 'Y');
}


/**
 * Truncate text to specified length
 * Strips HTML tags first to prevent broken HTML from ruining layout
 * 
 * @param string $text - The text to truncate (may contain HTML)
 * @param int $maxLength - Maximum length of output text
 * @return string - Truncated plain text
 */
function truncateText($text, $maxLength = 100)
{
    // Strip HTML tags first to get plain text
    $plainText = strip_tags($text);

    // Decode HTML entities
    $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');

    // Trim whitespace
    $plainText = trim($plainText);

    if (mb_strlen($plainText) <= $maxLength) {
        return htmlspecialchars($plainText, ENT_QUOTES, 'UTF-8');
    } else {
        // Truncate at word boundary to avoid cutting words in half
        $truncated = mb_substr($plainText, 0, $maxLength);
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > $maxLength * 0.8) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }
        return htmlspecialchars($truncated, ENT_QUOTES, 'UTF-8') . '...';
    }
}
