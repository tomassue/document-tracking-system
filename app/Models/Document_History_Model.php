<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_History_Model extends Model
{
    use HasFactory;

    protected $table = "document_history";

    protected $fillable = [
        'document_id',
        'status',
        'user_id',
        'remarks'
    ];
}
