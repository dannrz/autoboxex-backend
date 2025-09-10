<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordRestore extends Model
{
    protected $table = 'password_restore_request';

    protected $hidden = [
        'id',
        'password',
        'requested_at',
    ];

    protected $fillable = [
        'user_id',
        'password',
        'requested_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
