<?php

namespace Alexusmai\EasySettings\Models;

use Illuminate\Database\Eloquent\Model;

class EasySettings extends Model
{
    protected $table = 'easy_settings';

    protected $guarded = ['id'];

    protected $casts = [
        'schema'    => 'array',
        'data'      => 'array'
    ];
}