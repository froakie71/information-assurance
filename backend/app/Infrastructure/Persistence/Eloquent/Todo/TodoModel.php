<?php

namespace App\Infrastructure\Persistence\Eloquent\Todo;

use Illuminate\Database\Eloquent\Model;

class TodoModel extends Model
{
    protected $table = 'todos';
    protected $fillable = ['title', 'description', 'isCompleted', 'image', 'created_at', 'updated_at'];
}
