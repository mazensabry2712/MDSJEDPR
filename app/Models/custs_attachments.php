<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class custs_attachments extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public function Custs ()
    {
        return $this->belongsTo(Cust::class);
    }
}
