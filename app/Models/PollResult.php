<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PollResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'poll_id',
    ];

    protected $with = ['user', 'pollAnswers'];

    public function getId(): int
    {
        return $this->id;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    public function pollAnswers(): BelongsToMany
    {
        return $this->belongsToMany(PollAnswer::class, 'poll_answer_poll_result');
    }
}
