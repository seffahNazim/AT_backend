<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification1;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends User
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'user_id',
        'permission_id',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Notification1()
    {
        return $this->belongsToMany(Admin::class, 'notification1_admin', 'admin_id', 'notification1_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
