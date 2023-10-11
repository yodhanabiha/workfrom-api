<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomFacilities extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_room', 'internet', 'building_access', 'print_service', 'coffe', 'chair', 'table', 'location_status'
    ];

}