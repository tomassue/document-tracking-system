<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OutgoingCategoryVoucherModel extends Model
{
    use HasFactory;

    protected $table = "outgoing_category_voucher";

    protected $fillable = [
        'voucher_name'
    ];

    public function outgoing_documents(): MorphOne
    {
        return $this->morphOne(OutgoingDocumentsModel::class, 'category');
    }
}
