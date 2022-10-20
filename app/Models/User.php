<?php

namespace App\Models;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\MailResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $password
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    const ADMIN_ROLE_ID = 1;

    const WORKER_ROLE_ID = 2;

    const HR_ROLE_ID = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_image',
        'first_name',
        'middle_name',
        'last_name',
        'birthday',
        'gender',
        'region',
        'area',
        'town',
        'post_office',
        'email',
        'password',
        'linkedin',
        'facebook',
        'work_time',
        'position',
        'hire_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function Birthday(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
            set: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

    protected function hireDate(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
            set: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date:Y-m-d',
        'hire_date' => 'date:Y-m-d',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getAvatar(): ?string
    {
        return $this->profile_image;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getMiddleName(): ?string
    {
        return $this->middle_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getFullName(): string
    {
        return preg_replace('/\s+/', ' ', $this->first_name.' '.$this->middle_name.' '.$this->last_name);
    }

    public function getShortName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function getPostOffice(): ?string
    {
        return $this->post_office;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function getWorkTime(): ?string
    {
        return $this->work_time;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function getHireDate(): ?string
    {
        return $this->hire_date;
    }

    public function getVerifiedEmail()
    {
        return $this->email_verified_at;
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new EmailVerificationNotification());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    public function marital_status(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function getMaritalStatus(): ?int
    {
        return $this->marital_status ? $this->marital_status->getId() : null;
    }

    public function resume(): hasOne
    {
        return $this->hasOne(Resume::class);
    }

    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class, 'user_id');
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'user_id', 'id');
    }

    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function emergencyContact(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function getEmergencyContact(): Collection
    {
        return $this->emergencyContact;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRole(): ?int
    {
        return $this->role ? $this->role->getId() : null;
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function getManagerId(): ?int
    {
        return $this->manager_id;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public static function isAdmin(): ?bool
    {
        return  \Auth::user()->role_id === 1;
    }

    public static function isWorker(): ?bool
    {
        return  \Auth::user()->role_id === 2;
    }

    public static function isHR(): ?bool
    {
        return  \Auth::user()->role_id === 3;
    }

    public function createdPolls(): HasMany
    {
        return $this->hasMany(Poll::class, 'created_by');
    }

    public function pollResult(): HasMany
    {
        return $this->hasMany(PollResult::class);
    }

    public function getPollResult(): Collection
    {
        return $this->pollResult;
    }

    public function polls(): BelongsToMany
    {
        return $this->belongsToMany(Poll::class, 'poll_user');
    }

    public function vacations(): HasMany
    {
        return $this->hasMany(VacationOrHospital::class);
    }
}
