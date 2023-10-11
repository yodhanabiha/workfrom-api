<?php

namespace App\Events;

class RoomBooked
{
    public $transRoom;

    public function __construct($transRoom)
    {
        $this->transRoom = $transRoom;
    }
}