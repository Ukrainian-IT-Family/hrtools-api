<?php

declare(strict_types=1);

namespace App\Actions\User;

use Illuminate\Database\Eloquent\Collection;

final class GetUserCollectionResponse extends UserCollectionResponse
{
    public function __construct(private $users)
    {
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }
}
