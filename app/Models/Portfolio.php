<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One saved portfolio. The table is "portfolios" in the Railway PostgreSQL database.
 * The five list sections (education, skills, projects, work experience, social links)
 * are stored in JSONB columns and are read and written as PHP arrays.
 */
class Portfolio extends Model
{
    protected $table = 'portfolios';

    // The owner can never be set from a web form (mass assignment).
    protected $guarded = ['id', 'user_id'];

    protected $casts = [
        'education' => 'array',
        'skills' => 'array',
        'projects' => 'array',
        'work_experience' => 'array',
        'social_links' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
