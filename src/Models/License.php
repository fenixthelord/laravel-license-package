<?php


namespace Fenixthelord\License\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'key', 
        'domain',
        'valid_until',
        'is_active'
    ];
}
