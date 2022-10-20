<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'emergency_contact_id',
    ];

    public function emergency_contact(): BelongsTo
    {
        return $this->belongsTo(EmergencyContact::class, 'emergency_contact_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
