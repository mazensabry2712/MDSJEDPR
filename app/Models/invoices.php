<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoices extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    protected $fillable = [
        "invoice_number",
        'pr_number',
        'value',
        'invoice_copy_path',
        'status',
        'pr_invoices_total_value'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Relationships
     */

    // Define the relationship with the Project model
    public function project()
    {
    return $this->belongsTo(Project::class, 'pr_number', 'id');
    }
}
