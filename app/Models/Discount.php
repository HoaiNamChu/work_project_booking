<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory; 
    protected $table = 'discount'; 
    protected $fillable = [ 'room_id', 'discount_percent', 'start_date', 'end_date', ];
    public function discounts()
{
    return $this->hasMany(Discount::class, 'room_id');
}

}