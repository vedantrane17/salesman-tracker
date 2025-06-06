<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('salesman-location.{userId}', function ($user, $userId) {
    \Log::info('Broadcast channel auth attempt by user:', ['user' => $user]);
    return (int) $user->id === (int) $userId;
});


// Broadcast::channel('private-salesman-location.{userId}', function ($user, $userId) {
//     \Log::info('Broadcast channel auth attempt by user:', ['user' => $user]);
//     return (int) $user->id === (int) $userId;
// });

