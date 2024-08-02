<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File_Data_Model extends Model
{
    use HasFactory;

    protected $table = 'file_data';
    protected $fillable = [
        'file_name',
        'file_size',
        'file_type',
        'file',
        'user_id'
    ];
}
