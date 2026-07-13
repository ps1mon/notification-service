<?php

namespace App\Notifications\Channels;

use InvalidArgumentException;

class ChannelFactory
{
    public function make(string $channel): NotificationChannelInterface
    {
        $map = config('notification_channels');

        if (! isset($map[$channel])) {
            throw new InvalidArgumentException("Unknown channel: {$channel}");
        }

        return app($map[$channel]);
    }
}
