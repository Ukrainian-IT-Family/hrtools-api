<?php

namespace App\Actions\EmergencyContact;

use App\Actions\EmergencyPhone\UpdateEmergencyPhonesAction;
use App\Repository\EmergencyContactRepository;

final class UpdateEmergencyContactsAction
{
    public function __construct(private EmergencyContactRepository $repository,
    private UpdateEmergencyPhonesAction $emergencyPhonesAction)
    {
    }

    public function execute(int $userId, array $emergencyContactsArray): void
    {
        $emergencyContacts = $this->repository->getByUserId($userId);
        $i = 0;
        foreach ($emergencyContacts as $emergencyContact) {
            if ($i + 1 > count($emergencyContactsArray)) {
                $this->repository->delete($emergencyContact['id']);
            } else {
                $contact = $this->repository->getById($emergencyContact['id']);
                $contact->full_name = $emergencyContactsArray[$i]['fullName'];
                $contact->relationship = $emergencyContactsArray[$i]['relationship'];
                $this->repository->save($contact);
                $this->emergencyPhonesAction->execute($contact->id, $emergencyContactsArray[$i]['emergencyPhones']);
            }
            $i++;
        }
        for ($i; $i < count($emergencyContactsArray); $i++) {
            $contact = $this->repository->create(
                [
                    'full_name' => $emergencyContactsArray[$i]['fullName'],
                    'relationship' => $emergencyContactsArray[$i]['relationship'],
                    'user_id' => $userId,
                ]
            );
            $this->repository->save($contact);
            $this->emergencyPhonesAction->execute($contact->id, $emergencyContactsArray[$i]['emergencyPhones']);
        }
    }
}
