<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JustificationFile extends Model
{
    protected $fillable = [
        'justification_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function justification(): BelongsTo
    {
        return $this->belongsTo(Justification::class);
    }
}
