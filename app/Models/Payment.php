<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    protected $fillable = [
        'booking_id',
        'amount',
        'tax',
        'total_amount',
        'payment_date',
        'payment_method'
    ];

    public $timestamps = false;
}
