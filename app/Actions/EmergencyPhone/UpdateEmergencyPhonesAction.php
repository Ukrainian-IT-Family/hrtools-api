<?php

namespace App\Actions\EmergencyPhone;

use App\Repository\EmergencyPhoneRepository;

final class UpdateEmergencyPhonesAction
{
    public function __construct(private EmergencyPhoneRepository $repository)
    {
    }

    public function execute(int $emergencyId, array $emergencyPhoneArray): void
    {
        $emergencyPhones = $this->repository->getByEmergencyContactId($emergencyId);
        $j = 0;
        foreach ($emergencyPhones as $emergencyPhone) {
            if ($j + 1 > count($emergencyPhoneArray)) {
                $this->repository->delete($emergencyPhone['id']);
            } else {
                $phone = $this->repository->getById($emergencyPhone['id']);
                $phone->phone = $emergencyPhoneArray[$j]['phone'];
                $this->repository->save($phone);
            }
            $j++;
        }
        if ($j < count($emergencyPhoneArray)) {
            $phone = $this->repository->create(
                [
                    'phone' => $emergencyPhoneArray[$j]['phone'],
                    'emergency_contact_id' => $emergencyId,
                ]
            );
            $this->repository->save($phone);
        }
    }
}
