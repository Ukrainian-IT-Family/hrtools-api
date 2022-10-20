<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\User\UserCollectionResponse;
use App\Models\User;
use App\Repository\UserRepository;

final class UserArrayPresenter
{
    public function __construct(
        private ChildArrayPresenter $childArrayPresenter,
        private PhoneArrayPresenter $phoneArrayPresenter,
        private EmergencyContactArrayPresenter $emergencyContactArrayPresenter,
        private ResumeArrayPresenter $resumeArrayPresenter,
        private UserRepository $userRepository
    ) {
    }

    public function present(User $user): array
    {
        $workers = new UserCollectionResponse($this->userRepository->getUsersByHR($user->getId()));

        return [
            'avatar' => $user->getAvatar(),
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'middleName' => $user->getMiddleName(),
            'lastName' => $user->getLastName(),
            'birthday' => $user->getBirthday(),
            'gender' => (int) $user->getGender(),
            'maritalStatus' => $user->getMaritalStatus(),
            'children' => $this->childArrayPresenter->presentCollections($user->getChildren()),
            'region' => $user->getRegion(),
            'area' => $user->getArea(),
            'town' => $user->getTown(),
            'postOffice' => $user->getPostOffice(),
            'email' => $user->getEmail(),
            'contactsPhones' => $this->phoneArrayPresenter->presentCollections($user->getPhones()),
            'linkedin' => $user->getLinkedin(),
            'facebook' => $user->getFacebook(),
            'resume' => $user->getResume() ? $this->resumeArrayPresenter->present($user->getResume()) : null,
            'emergency' => $this->emergencyContactArrayPresenter->presentCollections($user->getEmergencyContact()),
            'email_verified_at' => $user->getVerifiedEmail(),
            'role' => $user->getRole(),
            'workers' => $this->getShortInfoCollections($workers),
            'managerId' => $user->getManagerId(),
            'manager' => $user->getManager() ? $this->shortInfo($user->getManager()) : null,
            'fullName' => $user->getFullName(),
            'shortName' => $user->getShortName(),
            'workTime' => $user->getWorkTime(),
            'position' => $user->getPosition(),
            'hireDate' => $user->getHireDate(),
        ];
    }

    public function getCollections(UserCollectionResponse $users): array
    {
        return $users->getUsers()
            ->map(
                function (User $user) {
                    return $this->present($user);
                }
            )
            ->all();
    }

    public function shortInfo(User $user): array
    {
        return [
            'id' => $user->getId(),
            'avatar' => $user->getAvatar(),
            'shortName' => $user->getShortName(),
            'fullName' => $user->getFullName(),
            'firstName' => $user->getFirstName(),
            'middleName' => $user->getMiddleName(),
            'lastName' => $user->getLastName(),
            'managerId' => $user->getManagerId(),
            'role' => $user->getRole(),
            'position' => $user->getPosition(),
            'hireDate' => $user->getHireDate(),
        ];
    }

    public function getShortInfoCollections(UserCollectionResponse $users): array
    {
        return $users->getUsers()
            ->map(
                function (User $user) {
                    return $this->shortInfo($user);
                }
            )
            ->all();
    }
}
