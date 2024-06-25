<?php
defined('BASEPATH') OR die('No direct script access allowed!');

if (!function_exists('bulan')) {
    function bulan($bulan)
    {
        $nama_bulan = [
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
            12 => 'Desember',
        ];
        return isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : '';
    }
}

