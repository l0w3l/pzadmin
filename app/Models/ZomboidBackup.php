<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZomboidBackup extends Model
{
    protected $fillable = [
        'file_path',
        'file_size',
        'hash',
    ];
}
