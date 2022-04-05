<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserOffline;

class UserOfflineController extends Controller
{
    public function __invoke(User $user)
    {
        $user = User::find($user->id);
        $user->status = 'offline';
        $user->save();

        broadcast(new UserOffline($user));
    }
}
