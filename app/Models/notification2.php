<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification2 extends Model
{
    use HasFactory;

    protected $fillable = [
        'employe_id',
        'text',
        'type',
        'read_at'
    ];

    public function employe()
    {
        return $this->belongsTo(employe::class, 'employe_id');
    }
}
