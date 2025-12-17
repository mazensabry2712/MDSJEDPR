<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ds extends Model
{
    use HasFactory;

    protected $table = 'ds';

    protected $fillable = [
        'dsname',
        'ds_contact'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
