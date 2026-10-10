<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One saved portfolio. The table is "portfolios" in the Railway PostgreSQL database.
 * The five list sections (education, skills, projects, work experience, social links)
 * are stored in JSONB columns and are read and written as PHP arrays.
 */
class Portfolio extends Model
{
    protected $table = 'portfolios';

    protected $guarded = [];

    protected $casts = [
        'education' => 'array',
        'skills' => 'array',
        'projects' => 'array',
        'work_experience' => 'array',
        'social_links' => 'array',
    ];
}
