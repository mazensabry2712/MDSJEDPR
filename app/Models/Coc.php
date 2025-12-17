<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coc extends Model
{
    use HasFactory;

    protected $fillable = [
        'coc_copy',
        'pr_number',
    ];

    public function coc()
    {
    return $this->belongsTo(Project::class);
    }

    public function project()
    {
    return $this->belongsTo(Project::class, 'pr_number');
    }

}
