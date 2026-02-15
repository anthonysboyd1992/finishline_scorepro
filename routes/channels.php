<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('event.{eventId}.operator.{operatorToken}', function ($user, $eventId, $operatorToken) {
    return $user !== null;
});
