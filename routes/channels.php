<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('event.{eventId}', function ($user, $eventId) {
    return $user !== null;
});
