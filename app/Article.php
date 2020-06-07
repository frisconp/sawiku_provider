<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    public function getThumbnailAttribute($thumbnail)
    {
        return Storage::url($thumbnail);
    }

    public function getCreatedAtAttribute($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $months = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $dayName = date('l', strtotime($date));

        $splitedDate = explode('-', date('Y-m-d', strtotime($date)));

        return $days[$dayName] . ', ' . $splitedDate[2] . ' ' . $months[(int) $splitedDate[1]] . ' ' . $splitedDate[0];
    }

    public function getUpdatedAtAttribute($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $months = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $dayName = date('l', strtotime($date));

        $splitedDate = explode('-', date('Y-m-d', strtotime($date)));

        return $days[$dayName] . ', ' . $splitedDate[2] . ' ' . $months[(int) $splitedDate[1]] . ' ' . $splitedDate[0];
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }
}
