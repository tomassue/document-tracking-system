<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ref_Offices_Model extends Model
{
    use HasFactory;

    protected $table = "ref_offices";
    protected $fillable = "office_name";
}
