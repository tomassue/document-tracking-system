<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OutgoingDocumentsModel extends Model
{
    use HasFactory;

    protected $table = "outgoing_documents";

    protected $fillable = [
        'document_no',
        'date',
        'document_details',
        'destination',
        'person_responsible',
        'attachments',
        'category_id_type',
        'category_id_id'
    ];

    // Set the primary key to 'document_no'
    protected $primaryKey = 'document_no';

    // Disable auto-incrementing since the primary key is a string
    public $incrementing = false;

    // Set the primary key type to string
    protected $keyType = 'string';

    // Define a starting point for the sequence
    protected static $startingNumber = 1;

    // Public static method to access the starting number
    public static function getStartingNumber()
    {
        return self::$startingNumber;
    }

    // Override the boot method to generate a custom ID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($documentNo) {
            // Get the last inserted document_no
            $lastDocumentNo = self::orderBy('document_no', 'desc')->first();

            if ($lastDocumentNo) {
                // Extract the numeric part of the document_no
                $lastIdNumber = intval(substr($lastDocumentNo->document_no, 9));
                // Increment the numeric part by 1
                $newIdNumber = $lastIdNumber + 1;
            } else {
                // If no records exist, start from 1
                $newIdNumber = 1;
            }

            // Calculate dynamic padding length based on the number
            $padLength = max(2, strlen((string)($newIdNumber)));

            // Format the new document_no with dynamic padding
            $documentNo->document_no = 'DOCUMENT-' . str_pad($newIdNumber, $padLength, '0', STR_PAD_LEFT);
        });
    }

    public function category(): MorphTo
    {
        return $this->morphTo();
    }
}
