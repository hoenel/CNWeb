<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $primaryKey = 'medicine_id';
    protected $fillable = ['name', 'brand', 'dosage', 'form', 'price', 'stock'];
    public function sales()
    {
        return $this->hasMany(Sales::class, 'medicine_id', 'medicine_id');
    }
    use HasFactory;
}