<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Child\UpdateChildrenAction;
use App\Actions\EmergencyContact\UpdateEmergencyContactsAction;
use App\Actions\Phone\UpdateContactPhonesAction;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;

final class UpdateProfileAction
{
    public function __construct(
        private UserRepository $userRepository,
        private UpdateChildrenAction $childrenAction,
        private UpdateContactPhonesAction $contactPhonesAction,
        private UpdateEmergencyContactsAction $emergencyContactsAction
    ) {
    }

    public function execute(UpdateProfileRequest $request): UpdateProfileResponse
    {
        $user = Auth::user();
        $userId = $user->id;

        $user->first_name = $request->getFirstName() ?: $user->first_name;
        $user->middle_name = $request->getMiddleName() ?: $user->middle_name;
        $user->last_name = $request->getLastName() ?: $user->last_name;
        $user->birthday = $request->getBirthday() ?: $user->birthday;
        $user->gender = $request->getGender() ?? $user->gender;
        $user->marital_status_id = $request->getMaritalStatus() ?: $user->marital_status_id;
        $user->region = $request->getRegion() ?: $user->region;
        $user->area = $request->getArea() ?: $user->area;
        $user->town = $request->getTown() ?: $user->town;
        $user->post_office = $request->getPostOffice() ?: $user->post_office;
        $user->email = $request->getEmail() ?: $user->email;
        $user->linkedin = $request->getLinkedin() ?: $user->linkedin;
        $user->facebook = $request->getFacebook() ?: $user->facebook;

        $this->contactPhonesAction->execute($userId, $request->getPhones());

        $this->childrenAction->execute($userId, $request->getChildren());

        $this->emergencyContactsAction->execute($userId, $request->getEmergencyContacts());

        $user = $this->userRepository->save($user);

        return new UpdateProfileResponse($user);
    }
}
