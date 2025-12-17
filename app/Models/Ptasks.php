<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptasks extends Model
{
    use HasFactory;
    protected $fillable=[
        'task_date', 'pr_number','details','assigned','expected_com_date','status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'pr_number');
    }
}
