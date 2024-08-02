<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incoming_Request_Model extends Model
{
    use HasFactory;

    protected $table = 'incoming_request';

    protected $fillable = [
        'incoming_category',
        'office_or_barangay_or_organization',
        'request_date',
        'category',
        'start_time',
        'end_time',
        'description',
        'files'
    ];
}
