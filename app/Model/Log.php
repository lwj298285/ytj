<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //
    protected $table = 'log';
    public $incrementing=false;
    protected $guarded = [];
    const CREATED_AT = 'add_time';
}
