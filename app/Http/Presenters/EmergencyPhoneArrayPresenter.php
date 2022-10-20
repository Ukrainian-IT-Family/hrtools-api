<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Models\EmergencyPhone;
use Illuminate\Database\Eloquent\Collection;

final class EmergencyPhoneArrayPresenter
{
    public function present(EmergencyPhone $emergencyPhone): array
    {
        return [
            'id' => $emergencyPhone->getId(),
            'phone' => $emergencyPhone->getPhone(),
        ];
    }

    public function presentCollections(Collection $emergencyPhones): array
    {
        return $emergencyPhones
            ->map(
                function (EmergencyPhone $emergencyPhone) {
                    return $this->present($emergencyPhone);
                }
            )
            ->all();
    }
}
