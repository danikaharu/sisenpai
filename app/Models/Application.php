<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'explanation', 'file', 'start_date', 'end_date', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
