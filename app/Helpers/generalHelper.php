<?php

if (!function_exists('getServerDate')) {
    function getServerDate($day = true, $time = false){
        $hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $date = '';
        if($day){
            $date.= $hari[date('w')].', ';
        }
        $date .= date('j').' '.$bulan[date('n') - 1].' '.date('Y');
        if($time){
            $date .= "&nbsp;&nbsp;&nbsp;".date("G:i:s");
        }
        return $date;
    }
}
