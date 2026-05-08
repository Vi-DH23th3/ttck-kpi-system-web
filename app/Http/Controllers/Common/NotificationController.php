<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return redirect()->route('dashboard');
        }

        $notification->markAsRead();
        // $targetLink = $notification->data['link'] ?? route('profile.index');

        try {
            return redirect($notification->data['link'] ?? route('profile.index'));
        } catch (\Exception $e) {
            return redirect()->route('profile.index');
        }

    }
}
