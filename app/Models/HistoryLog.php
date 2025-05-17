<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'location_state',
        'location_city',
        'used_from',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
