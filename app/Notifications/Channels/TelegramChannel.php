<?php

namespace App\Notifications\Channels;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class TelegramChannel implements NotificationChannelInterface
{
    public function send(Notification $notification): void
    {
        Log::info("Sending TG to user {$notification->user_id}: {$notification->message}");
    }
}
