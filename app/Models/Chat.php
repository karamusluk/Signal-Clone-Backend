<?php

namespace App\Models;

use Core\Model;

class Chat extends Model {

    protected $fillable = [
        'name',
        'owner',
    ];
}