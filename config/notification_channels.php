<?php

use App\Notifications\Channels\EmailChannel;
use App\Notifications\Channels\TelegramChannel;

return [
    'email' => EmailChannel::class,
    'telegram' => TelegramChannel::class,
];
