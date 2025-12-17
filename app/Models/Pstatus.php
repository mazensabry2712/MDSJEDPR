<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pstatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'pr_number',
        'date_time',
        'pm_name',
        'status',
        'actual_completion',
        'expected_completion',
        'date_pending_cost_orders',
        'notes',

    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'pr_number');
    }

    public function ppm()
    {
        return $this->belongsTo(ppms::class, 'pm_name');
    }
}
