<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Models\EmergencyContact;
use Illuminate\Database\Eloquent\Collection;

final class EmergencyContactArrayPresenter
{
    public function __construct(private EmergencyPhoneArrayPresenter $emergencyPhoneArrayPresenter)
    {
    }

    public function present(EmergencyContact $emergencyContact): array
    {
        return [
            'id' => $emergencyContact->getId(),
            'fullName' => $emergencyContact->getFullName(),
            'relationship' => $emergencyContact->getRelationship(),
            'emergencyPhones' => $this->emergencyPhoneArrayPresenter->presentCollections($emergencyContact->getEmergencyPhones()),
        ];
    }

    public function presentCollections(Collection $emergencyContacts): array
    {
        return $emergencyContacts
            ->map(
                function (EmergencyContact $emergencyContact) {
                    return $this->present($emergencyContact);
                }
            )
            ->all();
    }
}
