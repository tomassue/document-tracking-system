<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ref_Venue_Model extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_venues";

    protected $fillable = [
        'venue'
    ];
}
