<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestones extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_number',
        'milestone',
        'planned_com',
        'actual_com',
        'status',
        'comments'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'pr_number', 'id');
    }
}
