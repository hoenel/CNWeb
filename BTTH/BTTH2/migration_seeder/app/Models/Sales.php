<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $primaryKey = 'sale_id';
    protected $fillable = ['medicine_id', 'quantity', 'sale_date', 'customer_phone'];
    use HasFactory;
}

    // $table->id('sale_id');
    //         $table->unsignedBigInteger('medicine_id');
    //         $table->integer('quantity');
    //         $table->dateTime('sale_date');
    //         $table->string('customer_phone', 10)->nullable();
    //         $table->timestamps();
            
    //         $table->foreign('medicine_id')->references('medicine_id')->on('medicine');