<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoryseo extends Model
{
    use HasFactory;
    protected $table = 'categoryseos';
    // protected $primaryKey = 'Category';
    protected $fillable = [
        'Category'
    ];
}
