<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Models\Resume;

final class ResumeArrayPresenter
{
    public function present(Resume $resume): array
    {
        return [
            'id' => $resume->getId(),
            'name' => $resume->getName(),
            'path' => $resume->getPath(),
        ];
    }
}
