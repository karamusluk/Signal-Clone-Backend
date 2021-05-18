<?php

namespace App\Models;

use Core\Model;

class User extends Model {

    protected $fillable = [
        'name',
        'email',
        'password',
        'fullname',
        'imageUrl'
    ];
}