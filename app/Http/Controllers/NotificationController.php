<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return $this->successResponse($notifications);
    }

    public function unread(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->whereNull('lu_at')
            ->count();

        return $this->successResponse(['unread_count' => $count]);
    }

    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->update(['lu_at' => now()]);

        return $this->successResponse($notification, 'Notification marquée comme lue');
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->whereNull('lu_at')
            ->update(['lu_at' => now()]);

        return $this->successResponse(null, 'Toutes les notifications ont été marquées comme lues');
    }

    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        return $this->successResponse(null, 'Notification supprimée');
    }
}