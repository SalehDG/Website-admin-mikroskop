<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialCode extends Model
{
    protected $fillable = ['code', 'status', 'notes', 'used_at', 'customer_name', 'device_id_hash', 'device_name', 'last_validated_at'];

    protected $casts = ['used_at' => 'datetime', 'last_validated_at' => 'datetime'];

    public function scopeUnused($query)
    {
        return $query->where('status', 'unused');
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }
}
