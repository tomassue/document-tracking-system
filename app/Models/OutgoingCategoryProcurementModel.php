<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OutgoingCategoryProcurementModel extends Model
{
    use HasFactory;

    protected $table = "outgoing_category_procurement";

    protected $fillable = [
        'pr_no',
        'po_no'
    ];

    public function outgoing_documents(): MorphOne
    {
        return $this->morphOne(OutgoingDocumentsModel::class, 'category');
    }
}
