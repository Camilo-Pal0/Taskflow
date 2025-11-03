<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(20);
        return response()->json($notifications);
    }

    public function unreadCount(Request $request)
    {
        $count = $request->user()->notifications()->where('read', false)->count();
        return response()->json(['count' => $count]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->update(['read' => true]);
        return response()->json($notification);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->notifications()->where('read', false)->update(['read' => true]);
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();
        return response()->json(['message' => 'Notification deleted']);
    }
}