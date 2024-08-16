<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OutgoingCategoryOthersModel extends Model
{
    use HasFactory;

    protected $table = "outgoing_category_others";

    protected $fillable = [
        'document_name'
    ];

    public function outgoing_documents(): MorphOne
    {
        return $this->morphOne(OutgoingDocumentsModel::class, 'category');
    }
}
