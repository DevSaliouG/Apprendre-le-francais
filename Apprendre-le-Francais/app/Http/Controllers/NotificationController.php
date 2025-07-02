<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
   
 public function index()
    {
        return view('notifications.index', [
            'notifications' => Auth::user()->notifications()->paginate(20)
        ]);
    }

   public function fetch()
{
    $notifications = Auth::user()->notifications()
        ->take(10)
        ->get()
        ->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'Nouvelle notification',
                'icon' => $notification->data['icon'] ?? 'fas fa-bell',
                'color' => $notification->data['color'] ?? 'text-blue-500',
                'url' => $notification->data['url'] ?? '#',
                'read_at' => $notification->read_at,
                'time_ago' => $notification->created_at->diffForHumans()
            ];
        });

    return response()->json($notifications);
}
    
    
 public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
