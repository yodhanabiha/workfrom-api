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

class RoomDataController extends Controller
{

    public function getRooms(){
        $rooms = Rooms::all();
        return $rooms;
    }

    public function getRoomById($id){
        $room = Rooms::find($id);
        $check = $room->bookedCheck();

        if($check){

            $room->booked = 1;
            $room->save();

        }else{
            $room->booked = 0;
            $room->save();
        }

        return $room;
    }

    public function getImages($id){
       $room = Rooms::find($id);
    
        if ($room) {
            $images = $room->images()->get();
            return $images;
        } else {
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
    }

    public function getImage($id){
       $room = Rooms::find($id);
    
        if ($room) {
            $image = $room->images()->first();
            return $image;
        } else {
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
    }

    public function getTypes($id) {
        $room = Rooms::find($id);

        if ($room) {
            $type = $room->types()->get();
            return $type;
        } else {
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
        
    }

    public function getFacilities($id) {
        $room = Rooms::find($id);

        if ($room) {
            $facilities = $room->facilities()->get();
            return $facilities;
        } else {
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
        
    }

    public function getDistance(Request $request, $id) {

        $room = Rooms::find($id);

        $this->validate($request, [
            'userLatitude' => 'required',
            'userLongitude' => 'required',
        ]);


        if ($room) {

            $userLatitude = $request->userLatitude;
            $userLongitude = $request->userLongitude;

            $distance = $room->calculateDistance($userLatitude , $userLongitude);
            return $distance;

        } else {
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
        
    }

    public function postReview(Request $request, $id){
        $this->validate($request, [
            'rating' => 'required',
        ]);

        $userId = Auth::id();
        $room = Rooms::find($id);

        // Cek apakah pengguna sudah memberikan ulasan pada ruangan ini
        $existingReview = RoomReviews::where('id_room', $room->id)
            ->where('id_user', $userId)
            ->first();

        if ($existingReview) {
            // Jika ulasannya sudah ada, perbarui ulasannya
            $existingReview->update([
                'rating' => $request->rating,
            ]);
        } else {
            // Jika ulasannya belum ada, buat ulasan baru
            $review = RoomReviews::create([
                'id_room' => $room->id,
                'id_user' => $userId,
                'rating' => $request->rating,
            ]);

            $review->save();
        }

        return response()->json(['message' => 'rating berhasil'], 200);
    }


    public function sumReview($id) {
        $room = Rooms::find($id);

        if ($room) {
            $review = $room->sumReviews();
            return $review;
        } else {
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
        
    }

    public function createBooking(Request $request)
    {
        $this->validate($request, [
            'id_room' => 'required',
            'total_payment' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);

        $room = Rooms::find($request->id_room);
        $user = Auth::id();

        if ($room) {

            $transRoom = TransRoom::create([
                'id_room' => $request->id_room, 
                'id_user' => $user,
                'total_payment' => $request->total_payment,
                'start' => $request->start,
                'end' => $request->end,

            ]);

            $transRoom->save();


            return response()->json(['message' => 'transaksi berhasil'], 201);

        }else{
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
        
    }

    public function createBookingGuest(Request $request)
    {
        $this->validate($request, [
            'id_room' => 'required',
            'name' => 'required',
            'email' => 'required',
            'number' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);

        $room = Rooms::find($request->id_room);

        if ($room) {

            $transRoom = TransRoomGuest::create([
                'id_room' => $request->id_room, 
                'name' => $request->name,
                'email' => $request->email,
                'number' => $request->number,
                'company_name' => $request->company_name,
                'start' => $request->start,
                'end' => $request->end,
            ]);

            $transRoom->save();

            return response()->json(['message' => 'transaksi berhasil'], 201);

        }else{
            return response()->json(['message' => 'Ruangan tidak ditemukan'], 404);
        }
        
    }

     

}