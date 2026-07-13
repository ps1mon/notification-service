<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store(StoreNotificationRequest $request)
    {
        $notification = Notification::create([
            ...$request->validated(),
            'status' => NotificationStatus::Pending,
        ]);

        SendNotificationJob::dispatch($notification);

        return response()->json($notification, 201);
    }

    public function show(Notification $notification)
    {
        return response()->json($notification);
    }

    public function index(Request $request)
    {
        $notifications = Notification::query()
            ->where('user_id', $request->query('user_id'))
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->query('channel'), fn ($q, $c) => $q->where('channel', $c))
            ->latest()
            ->paginate(20);

        return response()->json($notifications);
    }
}
