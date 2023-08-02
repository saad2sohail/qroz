<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['title', 'due_date','status', 'completed', 'parent_id'];


    use SoftDeletes;

    protected $dates = ['deleted_at'];
    // Relationships (if any)
    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }
}
