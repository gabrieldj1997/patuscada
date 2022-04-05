<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserOnline;

class UserOnlineController extends Controller
{
    public function __invoke(User $user)
    {
        $user = User::find($user->id);
        $user->status = 'online';
        $user->save();

        broadcast(new UserOnline($user));
    }
}