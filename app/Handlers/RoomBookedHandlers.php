<?php

namespace App\Handlers;

use App\Events\RoomBooked;

class RoomBookedHandler
{
    public function handle(RoomBooked $event)
    {
        $room = $event->transRoom->room;
        $room->update(['booked' => true]);
    }
}