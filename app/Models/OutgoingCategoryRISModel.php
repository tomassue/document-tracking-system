<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OutgoingCategoryRISModel extends Model
{
    use HasFactory;

    protected $table = "outgoing_category_ris";

    protected $fillable = [
        'document_name',
        'ppmp_code'
    ];

    public function outgoing_documents(): MorphOne
    {
        return $this->morphOne(OutgoingDocumentsModel::class, 'category');
    }
}
