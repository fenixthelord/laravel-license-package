<?php


namespace Fenixthelord\License\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = ['key', 'product_id', 'valid_until', 'domain', 'is_active'];
}