<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'servant_id',
        'category_id',
        'service_date',
        'service_session',
        'service_time',
        'notes'
    ];

    protected $casts = [
        'service_date' => 'date',
        'service_time' => 'datetime',
    ];

    public function servant()
    {
        return $this->belongsTo(Servant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Helper untuk format tanggal
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->service_date)->locale('id')->isoFormat('dddd, D MMMM YYYY');
    }

    // Helper untuk nama sesi
    public function getSessionNameAttribute()
    {
        $sessions = [
            'KU1' => 'Ibadah Minggu Sesi 1 (KU1)',
            'KU2' => 'Ibadah Minggu Sesi 2 (KU2)',
            'KU3' => 'Ibadah Minggu Sesi 3 (KU3)',
        ];
        return $sessions[$this->service_session] ?? $this->service_session;
    }
}
