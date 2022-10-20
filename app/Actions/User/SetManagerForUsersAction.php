<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Notifications\AddHRToUserNotification;
use App\Notifications\UpdateHRToUserNotification;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Notification;

final class SetManagerForUsersAction
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function execute(int $managerId, array $workersArray): void
    {
        $workers = $this->repository->getUsersByHR($managerId);

        if (! empty($workersArray)) {
            foreach ($workersArray as $worker) {
                $user = $this->repository->getById($worker['id']);
                $oldManagerId = $user->manager_id;
                $user->manager_id = $managerId;
                $user = $this->repository->save($user);

                if ($oldManagerId == null && $oldManagerId != $managerId) {
                    Notification::send($user, new AddHRToUserNotification($user));
                }
                if ($oldManagerId != null && $oldManagerId != $managerId) {
                    Notification::send($user, new UpdateHRToUserNotification($user, $this->repository->getById($oldManagerId)));
                }
            }
        }

        foreach ($workers as $worker) {
            $user = $this->repository->getById($worker->id);
            $user->manager_id = null;
            $this->repository->save($user);
        }
    }
}
