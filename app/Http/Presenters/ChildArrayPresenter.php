<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Models\Child;
use Illuminate\Database\Eloquent\Collection;

final class ChildArrayPresenter
{
    public function present(Child $child): array
    {
        return [
            'id' => $child->getId(),
            'fullName' => $child->getFullName(),
            'gender' => (int) $child->getGender(),
            'birthday' => $child->getBirthday(),
        ];
    }

    public function presentCollections(Collection $children): array
    {
        return $children
            ->map(
                function (Child $child) {
                    return $this->present($child);
                }
            )
            ->all();
    }
}
