<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unreadCount()
    {
        $user = auth()->user();
        $latest = $user->unreadNotifications()->latest()->first();

        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'latest' => $latest ? [
                'id' => $latest->id,
                'title' => $latest->data['title'] ?? 'Notifikasi Baru',
                'message' => $latest->data['message'] ?? 'Ada request baru dari pelanggan',
                'tv_name' => $latest->data['tv_name'] ?? 'Meja',
            ] : null
        ]);
    }

    public function getNotifications()
    {
        $notifications = auth()->user()->unreadNotifications()->take(10)->get()->map(function ($notif) {
            return [
                'id' => $notif->id,
                'data' => $notif->data,
                'created_at' => $notif->created_at->diffForHumans(),
                'time' => $notif->created_at->format('H:i')
            ];
        });

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
