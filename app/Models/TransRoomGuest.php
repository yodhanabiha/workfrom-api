<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransRoomGuest extends Model
{
    use HasFactory;

    protected $table = 'trans_room_guest';

    protected $fillable = [
        'id_room',
        'name',
        'email',
        'number',
        'company_name',
        'start',
        'end'
    ];

}