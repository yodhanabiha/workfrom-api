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
use App\Models\RegistrationRoom;

use Illuminate\Support\Facades\DB;

class MitraController extends Controller
{
    public function index() {
        $userId = Auth::id();
        $rooms = Rooms::where('id_user', $userId)->get();
        return $rooms;
    }

    public function create(Request $request){

        $this->validate($request, [
            'title'  => 'required',
            'alamat'  => 'required', 
            'longitude'  => 'required', 
            'latitude'  => 'required',
            'harga'  => 'required', 
            'diskon'  => 'required',
            'capacity'  => 'required',
            'document' => 'required',
            'image' => 'required',
            'type' => 'required|array',
            'type.*' => 'required',
        ]);

        $promo = 0;

        if ($request->diskon == 0) { 
            $promo = 0;
        } else { 
            $promo = 1;
        };

        $userId = Auth::id();


        $room = Rooms::create([
            'id_user' => $userId,
            'title' => $request->title,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'harga' => $request->harga,
            'diskon' => $request->diskon,
            'capacity' => $request->capacity,
            'promo' => $promo,
            'approved' => 0,
            'booked' => 0,
            'rating' => 0,
        ]);
        $room->save();

        $registrationRoom = RegistrationRoom::create([
            'id_user' => Auth::id(), 
            'id_room' => $room->id,
            'document' => $request->document
        ]);
        $registrationRoom->save();

        RoomFacilities::create([
            'id_room' => $room->id,
            'internet' => $request->internet,
            'building_access' => $request->building_access,
            'print_service' => $request->print_service,
            'coffe' => $request->coffe,
            'chair' => $request->chair,
            'table' => $request->table,
            'location_status' => $request->location_status,
        ]);


        foreach ($request->file('image') as $item) {
            $image = RoomImages::create([
                'id_room' => $room->id, 
                'image' => $item->get(),
            ]);

            $image->save();

        }   

        foreach ($request->type as $item) {
            $type = RoomTypes::create([
                'id_room' => $room->id, 
                'type' => $item,
            ]);

            $type->save();
        }


        return response()->json(['message' => 'Ruangan berhasil dibuat'], 200);
    }


    public function update(Request $request, $id) {
        $room = Rooms::find($id);
        $promo = 0;

        if ($room->id_user === auth()->id()) {

            $updateData = [];

            if ($request->has('title')) {
                $updateData['title'] = $request->title;
            }

            if ($request->has('alamat')) {
                $updateData['alamat'] = $request->alamat;
            }

            if ($request->has('latitude')) {
                $updateData['latitude'] = $request->latitude;
            }

            if ($request->has('longitude')) {
                $updateData['longitude'] = $request->longitude;
            }

            if ($request->has('harga')) {
                $updateData['harga'] = $request->harga;
            }

             if ($request->has('diskon')) {
                $updateData['diskon'] = $request->diskon;
            }

            if ($request->has('capacity')) {
                $updateData['capacity'] = $request->capacity;
            }

            if ($request->diskon == 0) { 
                $promo = 0;
            } else { 
                $promo = 1;
            };

            $updateData['promo'] = $promo;

       

            $room->update($updateData);
            $room->save();

            return response()->json(['message' => 'Ruangan berhasil diupdate'], 200);

        }else{

            return response()->json(['message' => 'Anda tidak memiliki izin untuk update ruangan ini'], 403);

        }

        
    }

    public function destroy($id) {
        $room = Rooms::find($id);
        $images = RoomImages::where('id_room', $id)->get();
        $types = RoomTypes::where('id_room', $id)->get();
        $facilities = RoomFacilities::where('id_room', $id)->first();
        $registration = RegistrationRoom::where('id_room', $id)->first();
        $riviews = RoomReviews::where('id_room', $id)->get();
        if ($room->id_user === auth()->id()) {

            foreach ($images as $image) {
                $image->delete();
            }

            foreach ($types as $type) {
                $type->delete();
            }

            
            $facilities->delete();
            $registration->delete();
            

            foreach ($riviews as $riview) {
                $riview->delete();
            }

            $room->delete();

            return response()->json(['message' => 'Ruangan berhasil dihapus'], 200);
        } else {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus ruangan ini'], 403);
        }
    }

    
}