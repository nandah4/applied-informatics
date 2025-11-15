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
