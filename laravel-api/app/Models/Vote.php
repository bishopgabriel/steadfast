<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item',
        'user_ip',
        'estimated_location',
    ];

    protected $hidden = [
        'estimated_location',
        'user_ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
