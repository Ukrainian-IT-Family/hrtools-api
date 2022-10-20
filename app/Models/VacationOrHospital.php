<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class VacationOrHospital extends Model
{
    use HasFactory;

    const VACATION_ID = 0;

    const HOSPITAL_ID = 1;

    const PENDING_STATUS = null;

    const CANCEL_STATUS = 0;

    const ACCEPT_STATUS = 1;

    const DEFAULT_LATEST_COUNT = 4;

    protected $fillable = [
        'user_id',
        'type',
        'date_start',
        'date_end',
        'days_count',
        'status',
        'comment',
    ];

    protected $with = [
        'author',
    ];

    public $casts = [
        'date_start' => 'date:Y-m-d',
        'date_end' => 'date:Y-m-d',
    ];

    protected function dateStart(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format('d.m.y'),
            set: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function dateEnd(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format('d.m.y'),
            set: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function createdAt(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format('d.m.y'),
        );
    }

    public function getCanDeleteAttribute(): bool
    {
        $userId = Auth::id();

        return $this->getStatus() === self::PENDING_STATUS && $this->getAuthorId() == $userId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getDateStart(): string
    {
        return $this->date_start;
    }

    public function getDateEnd(): string
    {
        return $this->date_end;
    }

    public function getDays(): int
    {
        return $this->days_count;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getAuthorId(): int
    {
        return $this->user_id;
    }
}
