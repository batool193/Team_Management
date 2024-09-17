<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\TaskPriorty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use  SoftDeletes ,HasFactory;
    protected $fillable = ['project_id','title', 'description', 'priority', 'due_date', 'status','notes'];
    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriorty::class,
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }


}
