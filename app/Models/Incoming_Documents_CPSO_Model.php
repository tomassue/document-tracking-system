<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incoming_Documents_CPSO_Model extends Model
{
    use HasFactory;

    protected $table = "incoming_documents_cpso";

    protected $fillable = [
        'document_no',
        'incoming_document_category',
        'document_info',
        'attachment',
        'date'
    ];

    // Set the primary key to 'document_no'
    // protected $primaryKey = 'document_no';

    // Disable auto-incrementing since the primary key is a string
    // public $incrementing = false;

    // Set the primary key type to string
    protected $keyType = 'string';

    // Define a starting point for the sequence
    // protected static $startingNumber = 34;

    // Public static method to access the starting number
    // public static function getStartingNumber()
    // {
    //     return self::$startingNumber;
    // }

    // Override the boot method to generate a custom ID
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($documentNo) {
    //         // Get the last inserted document_no
    //         $lastDocumentNo = self::orderBy('document_no', 'desc')->first();

    //         if ($lastDocumentNo) {
    //             // Extract the numeric part of the document_no
    //             // Adjust the extraction logic to correctly parse the numeric part
    //             $lastIdNumber = intval(substr($lastDocumentNo->document_no, 5));
    //             // Increment the numeric part by 1
    //             $newIdNumber = $lastIdNumber + 1;
    //         } else {
    //             // If no records exist, start from the defined starting number
    //             $newIdNumber = self::getStartingNumber();
    //         }

    //         // Calculate dynamic padding length based on the number
    //         $padLength = max(2, strlen((string)($newIdNumber)));

    //         // Format the new document_no with dynamic padding
    //         $documentNo->document_no = 'MEMO-' . str_pad($newIdNumber, $padLength, '0', STR_PAD_LEFT);
    //     });
    // }
}
