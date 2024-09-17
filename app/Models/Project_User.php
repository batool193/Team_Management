<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_User extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','user_id','contribution_hours','last_activity'];

    protected $casts = [
        'role' => Role::class,    ];


}
