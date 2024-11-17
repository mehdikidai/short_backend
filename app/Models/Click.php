<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Click extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_id',
        'ip_address',
        'country',
        'country_code',
        'browser',
        'device'
    ];

    public function url(): BelongsTo
    {
        return $this->belongsTo(URL::class);
    }
}
