<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedIp extends Model
{
    use HasFactory;

    protected $table = 'blocked_ips';

    protected $fillable = ['ip_address', 'country_id', 'blocked_at', 'reason'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
