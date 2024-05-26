<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'manage_employe',
        'manage_justification',
        'manage_pointing',
        'manage_admin',
        'send_notification',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class, 'permission_id');
    }
}
