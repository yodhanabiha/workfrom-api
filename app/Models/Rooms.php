<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rooms extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user', 'title', 'alamat', 'longitude', 'latitude', 'harga', 'diskon', 'capacity','promo', 'approved', 'booked', 'rating'
    ];

    public function registration()
    {
        return $this->hasOne(RegistrationRoom::class, 'id_room');
    }


    public function images()
    {
        return $this->hasMany(RoomImages::class, 'id_room');
    }

    public function facilities()
    {
        return $this->hasOne(RoomFacilities::class, 'id_room');
    }

    public function reviews()
    {
        return $this->hasMany(RoomReviews::class, 'id_room');
    }
    public function types()
    {
        return $this->hasMany(RoomTypes::class, 'id_room');
    }

    public function sumReviews(){
        return $this->reviews->count();
    }

    public function transRoom()
    {
        return $this->hasMany(TransRoom::class, 'id_room');
    }

    public function transRoomGuest()
    {
        return $this->hasMany(TransRoomGuest::class, 'id_room');
    }
    

    public function bookedCheck() {

        $today = Carbon::now()->toDateString();

        $isBooked = $this->transRoom->where('start', '<=', $today)
            ->where('end', '>=', $today)
            ->isNotEmpty();

        $isBookedGuest = $this->transRoomGuest->where('start', '<=', $today)
            ->where('end', '>=', $today)
            ->isNotEmpty();


        if ($isBooked) {

            return true;

        } else if ($isBookedGuest) {

            return true;

        } else {

            return false; 
        }
        
    }

    public function calculateRating()
    {

        $totalRating = $this->reviews->sum('rating');
        $totalReviews = $this->reviews->count();

        if ($totalReviews > 0) {
            return $totalRating / $totalReviews;
        } else {
            return 0; // Menghindari pembagian oleh nol
        }
        
    }

    public function calculateDistance($userLatitude, $userLongitude)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($userLatitude);
        $lon2 = deg2rad($userLongitude);

        $latDifference = $lat2 - $lat1;
        $lonDifference = $lon2 - $lon1;

        $a = sin($latDifference / 2) ** 2 + cos($lat1) * cos($lat2) * sin($lonDifference / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        $distance = round($distance);

        return $distance;
    }


}