<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dn extends Model
{
    use HasFactory;

    protected $table = 'dns';


    protected $fillable = [
        'dn_number',
        'pr_number',
        'dn_copy',
        'date',
    ];

    public function project()
    {
    return $this->belongsTo(Project::class, 'pr_number');
    }
}
