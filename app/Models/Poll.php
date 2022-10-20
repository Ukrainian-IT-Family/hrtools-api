<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Poll extends Model
{
    use HasFactory;

    const NEW_STATUS_ID = 1;

    const ACTIVE_STATUS_ID = 2;

    const COMPLETE_STATUS_ID = 3;

    const DEFAULT_LATEST_COUNT = 4;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (! $model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }
        });
    }

    protected $with = ['author', 'questions', 'workers', 'pollResult'];

    protected $withCount = ['workers', 'pollResult'];

    protected $fillable = [
        'title',
        'status',
        'anonymous',
    ];

    protected function createdAt(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format('d.m.Y')
        );
    }

    public function getPassedAttribute(): bool
    {
        $userId = Auth::id();

        return $this->pollResult()->where('user_id', $userId)->exists();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getAnonymous(): bool
    {
        return $this->anonymous;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAuthorId(): ?int
    {
        return $this->created_by;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function questions(): HasMany
    {
        return $this->hasMany(PollQuestion::class);
    }

    public function pollResult(): HasMany
    {
        return $this->hasMany(PollResult::class);
    }

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'poll_user');
    }
}
