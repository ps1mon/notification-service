<?php

namespace App\Models;

use App\Enums\NotificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message',
        'channel',
        'status',
        'attempts',
        'error',
    ];
    protected $casts = [
        'status' => NotificationStatus::class,
    ];
}
