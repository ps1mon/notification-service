<?php

namespace App\Notifications\Channels;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class EmailChannel implements NotificationChannelInterface
{
    public function send(Notification $notification): void
    {
        Log::info("Sending EMAIL to user {$notification->user_id}: {$notification->message}");
    }
}
