<?php

namespace App\Actions\Child;

use App\Repository\ChildRepository;

final class UpdateChildrenAction
{
    public function __construct(private ChildRepository $repository)
    {
    }

    public function execute(int $userId, array $childrenArray = []): void
    {
        $children = $this->repository->getByUserId($userId);
        if (! empty($children) || ! empty($childrenArray)) {
            $i = 0;
            foreach ($children as $child) {
                if ($i + 1 > count($childrenArray)) {
                    $this->repository->delete($child['id']);
                } else {
                    $userChild = $this->repository->getById($child['id']);
                    $userChild->full_name = $childrenArray[$i]['fullName'];
                    $userChild->gender = $childrenArray[$i]['gender'];
                    $userChild->birthday = $childrenArray[$i]['birthday'];
                    $this->repository->save($userChild);
                }
                $i++;
            }
            for ($i; $i < count($childrenArray); $i++) {
                $userChild = $this->repository->create(
                    [
                        'full_name' => $childrenArray[$i]['fullName'],
                        'gender' => $childrenArray[$i]['gender'],
                        'birthday' => $childrenArray[$i]['birthday'],
                        'user_id' => $userId,
                    ]
                );
                $this->repository->save($userChild);
            }
        }
    }
}
