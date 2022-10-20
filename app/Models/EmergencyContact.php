<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'relationship',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function emergency_phones(): HasMany
    {
        return $this->hasMany(EmergencyPhone::class, 'emergency_contact_id', 'id');
    }

    public function getEmergencyPhones(): Collection
    {
        return $this->emergency_phones;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function getRelationship(): int
    {
        return $this->relationship;
    }
}
