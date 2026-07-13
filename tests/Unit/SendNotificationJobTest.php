<?php

namespace Tests\Unit;

use App\Enums\NotificationStatus;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use App\Notifications\Channels\ChannelFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class SendNotificationJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_marks_notification_as_sent(): void
    {
        $notification = Notification::factory()->create([
            'status' => NotificationStatus::Pending,
            'channel' => 'email',
        ]);

        (new SendNotificationJob($notification))->handle(app(ChannelFactory::class));

        $this->assertEquals(NotificationStatus::Sent, $notification->fresh()->status);
        $this->assertSame(1, $notification->fresh()->attempts);
    }

    public function test_failed_marks_notification_as_failed_with_error(): void
    {
        $notification = Notification::factory()->create([
            'status' => NotificationStatus::Pending,
        ]);

        (new SendNotificationJob($notification))->failed(new RuntimeException('SMTP down'));

        $fresh = $notification->fresh();
        $this->assertEquals(NotificationStatus::Failed, $fresh->status);
        $this->assertSame('SMTP down', $fresh->error);
    }

    public function test_channel_factory_throws_on_unknown_channel(): void
    {
        $this->expectException(InvalidArgumentException::class);

        app(ChannelFactory::class)->make('sms');
    }
}
