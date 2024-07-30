<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Offices_Model extends Model
{
    use HasFactory;

    protected $table = "user_offices";
    protected $fillable = [
        'user_id',
        'office_id'
    ];
}
