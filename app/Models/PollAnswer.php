<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PollAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'poll_question_id',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function pollQuestion(): BelongsTo
    {
        return $this->belongsTo(PollQuestion::class, 'poll_question_id');
    }

    public function getQuestionId(): int
    {
        return $this->poll_question_id;
    }

    public function pollResults(): BelongsToMany
    {
        return $this->belongsToMany(PollResult::class, 'poll_answer_poll_result');
    }
}
