<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ref_Venue_Model extends Model
{
    use HasFactory;

    protected $table = "ref_venues";

    protected $fillable = [
        'venue',
        'is_active'
    ];
}
