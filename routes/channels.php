<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.Player.{id}', function ($user, $id) {
  return (int) $user->id === (int) $id;
});


Broadcast::channel('App.Chatroom', function ($user) {
  return $user;
});

Broadcast::channel('App.jogo-{id}', function ($user) {
  return $user;
});

Broadcast::channel('App.jogo.jogada-{id}', function ($user) {
  return $user;
});

Broadcast::channel('App.jogo.message-{id}', function ($user) {
  return $user;
});