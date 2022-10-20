<?php

declare(strict_types=1);

namespace App\Actions\User;

final class UpdateUserRequest
{
    public function __construct(
        private int $userId,
        private string $firstName,
        private ?string $middleName,
        private string $lastName,
        private ?string $birthday,
        private ?bool $gender,
        private ?int $maritalStatus,
        private ?array $children,
        private ?string $region,
        private ?string $area,
        private ?string $town,
        private ?string $postOffice,
        private array $phones,
        private string $email,
        private ?string $linkedin,
        private ?string $facebook,
        private ?array $emergencyContact,
        private ?int $role,
        private ?array $workers,
        private ?string $workTime,
        private ?string $position,
        private ?string $hireDate
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function getMaritalStatus(): ?int
    {
        return $this->maritalStatus;
    }

    public function getChildren(): ?array
    {
        return $this->children;
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
        return $this->postOffice;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhones(): array
    {
        return $this->phones;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function getEmergencyContacts(): ?array
    {
        return $this->emergencyContact;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function getWorkers(): ?array
    {
        return $this->workers;
    }

    public function getWorkTime(): ?string
    {
        return $this->workTime;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function getHireDate(): ?string
    {
        return $this->hireDate;
    }
}
