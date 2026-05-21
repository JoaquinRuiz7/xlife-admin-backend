<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveGlobalSetting extends Model
{
    use HasFactory;

    protected $table = 'active_global_security_settings';

    protected $fillable = ['setting_id', 'active'];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(ActiveGlobalSetting::class);
    }
}
