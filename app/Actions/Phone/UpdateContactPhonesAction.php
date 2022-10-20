<?php

namespace App\Actions\Phone;

use App\Repository\PhoneRepository;

final class UpdateContactPhonesAction
{
    public function __construct(private PhoneRepository $repository)
    {
    }

    public function execute(int $userId, array $phoneArray): void
    {
        $contactPhones = $this->repository->getByUserId($userId);
        $i = 0;
        foreach ($contactPhones as $contactPhone) {
            if ($i + 1 > count($phoneArray)) {
                $this->repository->delete($contactPhone['id']);
            } else {
                $phone = $this->repository->getById($contactPhone['id']);
                $phone->phone = $phoneArray[$i]['phone'];
                $this->repository->save($phone);
            }
            $i++;
        }
        if ($i < count($phoneArray)) {
            $phone = $this->repository->create(
                [
                    'phone' => $phoneArray[$i]['phone'],
                    'user_id' => $userId,
                ]
            );
            $this->repository->save($phone);
        }
    }
}
