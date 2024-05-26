<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pointing;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Notification2;
use Illuminate\Database\Eloquent\SoftDeletes;



class Employe extends User
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'full_name',
        'user_id',
        'birthday',
        'sexe',
        'photo',
        'is_deleted',
    ];

    protected $dates = ['deleted_at'];

    public function notification2()
    {
        return $this->hasmany(Notification2::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pointing(): HasMany
    {
        return $this->hasMany(Pointing::class, 'employe_id');
    }
}
