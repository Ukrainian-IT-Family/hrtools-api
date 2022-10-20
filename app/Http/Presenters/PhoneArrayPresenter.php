<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Collection;

final class PhoneArrayPresenter
{
    public function present(Phone $phone): array
    {
        return [
            'id' => $phone->getId(),
            'phone' => $phone->getPhone(),
        ];
    }

    public function presentCollections(Collection $phones): array
    {
        return $phones
            ->map(
                function (Phone $phone) {
                    return $this->present($phone);
                }
            )
            ->all();
    }
}
