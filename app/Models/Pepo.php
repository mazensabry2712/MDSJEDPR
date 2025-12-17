<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pepo extends Model
{
    use HasFactory;
    protected $fillable = [
        'pr_number',
        'category',
        'planned_cost',
        'selling_price',

    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'pr_number', 'id');
    }

}
