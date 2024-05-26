<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pointing;

class Justification extends Model
{
    use HasFactory;

    protected $fillable = [
        'pointing_id',
        'text',
        'file',
    ];

    public function pointage(): BelongsTo
    {
        return $this->belongsTo(Pointing::class, 'pointing_id');
    }
}
