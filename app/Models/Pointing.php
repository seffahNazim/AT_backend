<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employe;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Justification;


class Pointing extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'check_in',
        'check_out',
        'statut',
        'date',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }

    public function justification(): HasOne
    {
        return $this->hasOne(Justification::class, 'pointing_id');
    }
}
