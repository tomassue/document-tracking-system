<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ref_Category_Model extends Model
{
    use HasFactory;

    protected $table = "ref_category";

    protected $fillable = [
        'category',
        'document_type',
        'is_active',
        'created_by'
    ];
}
