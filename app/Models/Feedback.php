<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    // created_at is filled by the database (DEFAULT NOW()).
    public $timestamps = false;

    protected $fillable = ['message', 'user_id'];
}
