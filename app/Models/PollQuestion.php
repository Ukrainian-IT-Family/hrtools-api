<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'required',
        'type',
        'poll_id',
    ];

    protected $with = ['answers'];

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    public function getPollId(): int
    {
        return $this->poll_id;
    }

    public function getPoll(): Collection
    {
        return $this->poll;
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PollAnswer::class, 'poll_question_id');
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }
}
