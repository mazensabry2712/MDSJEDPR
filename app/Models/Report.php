<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_number',
        'project_name',
        'project_manager',
        'technologies',
        'customer_name',
        'customer_po',
        'value',
        'invoice_total',
        'customer_po_deadline',
        'actual_completion_percentage',
        'vendors',
        'suppliers',
        'am',
    ];

    protected $casts = [
        'customer_po_deadline' => 'date',
        'value' => 'decimal:2',
        'invoice_total' => 'decimal:2',
        'actual_completion_percentage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for filtering by minimum value
     */
    public function scopeValueMin($query, $value)
    {
        return $query->where('value', '>=', $value);
    }

    /**
     * Scope for filtering by maximum value
     */
    public function scopeValueMax($query, $value)
    {
        return $query->where('value', '<=', $value);
    }

    /**
     * Scope for filtering by deadline from
     */
    public function scopeDeadlineFrom($query, $date)
    {
        return $query->whereDate('customer_po_deadline', '>=', $date);
    }

    /**
     * Scope for filtering by deadline to
     */
    public function scopeDeadlineTo($query, $date)
    {
        return $query->whereDate('customer_po_deadline', '<=', $date);
    }

    /**
     * Scope for filtering by minimum completion percentage
     */
    public function scopeCompletionMin($query, $value)
    {
        return $query->where('actual_completion_percentage', '>=', $value);
    }

    /**
     * Scope for filtering by maximum completion percentage
     */
    public function scopeCompletionMax($query, $value)
    {
        return $query->where('actual_completion_percentage', '<=', $value);
    }
}
