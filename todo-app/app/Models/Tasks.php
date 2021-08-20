<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Database\Eloquent;

/**
 * Class Tasks
 * @package App\Models
 * @mixin \Eloquent
 */
class Tasks extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_name',
        'description',
        'status',

    ];
}
