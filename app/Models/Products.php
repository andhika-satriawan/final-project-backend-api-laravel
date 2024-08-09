<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'kode',
        'nama',
        'qty',
        'foto',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function getFotoAttribute($value)
    {
        return url('storage/product/' . $value);
    }
}
