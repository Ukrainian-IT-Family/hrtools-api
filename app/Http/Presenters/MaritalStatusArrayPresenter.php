<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\Handbook\MaritalStatusCollectionResponse;
use App\Actions\Handbook\MaritalStatusResponse;
use App\Models\MaritalStatus;

final class MaritalStatusArrayPresenter
{
    public function present(MaritalStatusResponse $status): array
    {
        return [
            'id' => $status->getMaritalStatusName(),
            'name' => $status->getMaritalStatusId(),
        ];
    }

    public function getCollections(MaritalStatusCollectionResponse $statuses): array
    {
        return $statuses->getMaritalStatus()
            ->map(
                function (MaritalStatus $status) {
                    return $this->present(new MaritalStatusResponse($status));
                }
            )
            ->all();
    }
}
