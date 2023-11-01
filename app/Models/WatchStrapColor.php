<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchStrapColor extends Model
{
    // use HasFactory;
    protected $table = 'tmaudd';
    public $timestamps = false;

    public function watchStrap() : HasMany {
        return $this->hasMany(WatchStrap::class, 'maMauDD', 'maMauDD');
    }
}
