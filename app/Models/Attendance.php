<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'longitude', 'latitude', 'time', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function type()
    {
        if ($this->type == 1) {
            return 'Absen Masuk';
        } elseif ($this->type == 2) {
            return 'Absen Pulang';
        } elseif ($this->type == 3) {
            return 'Absen Penugasan Masuk';
        } else {
            return 'Absen Penugasan Pulang';
        }
    }

    public function status()
    {
        if ($this->type == 1 || $this->type == 3) {
            if ($this->time > '09:00:00') {
                return 'Terlambat';
            } else {
                return 'Tepat Waktu';
            }
        } else {
            return 'Tepat Waktu';
        }
    }

    public function scopeCheckAttendance($query, $value)
    {
        return $query->whereDate('created_at', \Carbon\Carbon::today())
            ->where('user_id', auth()->user()->id)
            ->where('type', $value);
    }
}
