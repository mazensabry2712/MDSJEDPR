<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppos extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_number',
        'category',
        'dsname',
        'po_number',
        'value',
        'date',
        'status',
        'updates',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'value' => 'decimal:2'
    ];

    /**
     * Get the project that owns the PPO.
     */
    public function project()
    {
    return $this->belongsTo(Project::class, 'pr_number');
    }

    /**
     * Get the PEPO category that owns the PPO.
     */
    public function pepo()
    {
        return $this->belongsTo(Pepo::class, 'category');
    }

    /**
     * Get the DS that owns the PPO.
     */
    public function ds()
    {
        return $this->belongsTo(Ds::class, 'dsname');
    }
}
