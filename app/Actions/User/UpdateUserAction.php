<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Child\UpdateChildrenAction;
use App\Actions\EmergencyContact\UpdateEmergencyContactsAction;
use App\Actions\Phone\UpdateContactPhonesAction;
use App\Notifications\SetRoleNotification;
use App\Notifications\UpdateRoleNotification;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Notification;

final class UpdateUserAction
{
    public function __construct(
        private UserRepository $userRepository,
        private UpdateChildrenAction $childrenAction,
        private UpdateContactPhonesAction $contactPhonesAction,
        private UpdateEmergencyContactsAction $emergencyContactsAction,
        private SetManagerForUsersAction $setManagerForUsersAction
    ) {
    }

    public function execute(UpdateUserRequest $request): UpdateUserResponse
    {
        $userId = $request->getUserId();
        $user = $this->userRepository->getById($userId);

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

        $this->setManagerForUsersAction->execute($userId, $request->getWorkers());

        if ($user->role_id == null && $user->role_id != $request->getRole()) {
            Notification::send($user, new SetRoleNotification($request->getRole()));
        }
        if ($user->role_id != null && $user->role_id != $request->getRole()) {
            Notification::send($user, new UpdateRoleNotification($user->role_id, $request->getRole()));
        }
        $user->role_id = $request->getRole() ?? null;
        $user->work_time = $request->getWorkTime() ?? null;
        $user->position = $request->getPosition() ?? null;
        $user->hire_date = $request->getHireDate() ?? null;

        $user = $this->userRepository->save($user);

        return new UpdateUserResponse($user);
    }
}
