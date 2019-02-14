<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBranch extends Model
{
    protected $fillable = [
        'name',
        'parent'
    ];

    protected $table = 'store_branches';
}
