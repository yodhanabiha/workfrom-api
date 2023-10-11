<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rooms;
use App\Models\RoomFacilities;
use App\Models\RoomImages;
use App\Models\RoomReviews;
use App\Models\RoomTypes;
use App\Models\TransRoom;
use App\Models\TransRoomGuest;
use App\Events\RoomBooked;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function rooms() {
        $rooms = Rooms::where('approved', 0)->get();
        return $rooms;
    }

    public function checkDocument($id) {
        $document = Rooms::find($id)->registration()->get();
        return $document;
    }

    public function approveRoom($id){
        $room = Rooms::find($id);
        $room->approved = true;
        $room->save();
        return response()->json(['message' => 'Ruangan berhasil di approved'], 200);
    }

}