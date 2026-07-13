<?php

namespace App\Jobs;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use App\Notifications\Channels\ChannelFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(public Notification $notification) {}

    public function handle(ChannelFactory $factory): void
    {
        $channel = $factory->make($this->notification->channel);

        $this->notification->increment('attempts');
        $channel->send($this->notification);

        $this->notification->update(['status' => NotificationStatus::Sent]);
    }

    public function failed(Throwable $exception): void
    {
        $this->notification->update([
            'status' => NotificationStatus::Failed,
            'error' => $exception->getMessage(),
        ]);
    }
}
