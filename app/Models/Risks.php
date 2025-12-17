<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Risks extends Model
{
    use HasFactory;
    protected $fillable = [
        'pr_number',
        'risk',
        'impact',
        'mitigation',
        'owner',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'pr_number', 'id');
    }

}
