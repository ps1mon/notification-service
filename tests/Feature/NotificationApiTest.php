<?php

namespace Tests\Feature;

use App\Jobs\SendNotificationJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_notification_dispatches_job(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/notifications', [
            'user_id' => $user->id,
            'message' => 'Hello',
            'channel' => 'email',
        ]);

        $response->assertStatus(201);
        Queue::assertPushed(SendNotificationJob::class);
    }
}
