<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransRoom extends Model
{
    use HasFactory;

    protected $table = 'trans_room';

    protected $fillable = [
        'id_room',
        'id_user',
        'total_payment',
        'start',
        'end',
    ];

}