<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incoming_Documents_CPSO_Model extends Model
{
    use HasFactory;

    protected $table = "incoming_documents_cpso";

    protected $fillable = [
        'incoming_document_category',
        'document_info',
        'attachment',
        'date'
    ];

    // Disable auto-incrementing since the primary key is a string
    public $incrementing = false;

    // Set the primary key type
    protected $keyType = 'string';

    // Override the boot method to generate a custom ID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($memo) {
            // Get the last inserted memo ID
            $lastMemo = Incoming_Documents_CPSO_Model::orderBy('id', 'desc')->first();

            // Extract the numeric part of the ID
            $lastIdNumber = $lastMemo ? intval(substr($lastMemo->id, 5)) : 0;

            // Increment the numeric part by 1
            $newIdNumber = $lastIdNumber + 1;

            // Calculate dynamic padding length based on the number
            $padLength = max(2, strlen((string)($newIdNumber)));

            // Format the new ID with dynamic padding
            $memo->id = 'MEMO-' . str_pad($newIdNumber, $padLength, '0', STR_PAD_LEFT);
        });
    }
}
