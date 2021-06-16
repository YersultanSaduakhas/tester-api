<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The users that belong to the 
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
