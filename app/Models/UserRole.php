<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class UserRole extends Model
{
    //
    protected $fillable = [
        'user_id','role_id'
    ];

    public function data(){
        return $this->belongsTo(Role::class);
    }

}
