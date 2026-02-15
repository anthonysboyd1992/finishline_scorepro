<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class PillDrawUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    /**
     * @param  array<int, int>  $drawnPills
     */
    public function __construct(
        public int $eventId,
        public int $raceClassId,
        public string $driverName,
        public string $carNumber,
        public string $state,
        public ?int $assignedPill,
        public array $drawnPills,
        public int $pillMin,
        public int $pillMax,
        public string $operatorToken = '',
    ) {}

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("event.{$this->eventId}.operator.{$this->operatorToken}"),
        ];
    }
}
