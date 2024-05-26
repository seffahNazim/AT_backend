<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Notification1 extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'type',
    ];

    public function admin()
    {
        return $this->belongsToMany(Admin::class, 'notification1_admin', 'notification1_id', 'admin_id')->withPivot('admin_id');;
    }
}
