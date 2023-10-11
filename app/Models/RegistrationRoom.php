<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationRoom extends Model
{
    use HasFactory;

    protected $table = 'registration_room';


    protected $fillable = [
        'id_user', 'id_room', 'document'
    ];

}