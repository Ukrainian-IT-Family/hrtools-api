<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UserRepository
{
    public function create(array $fields): User
    {
        return User::create($fields);
    }

    /**
     * @param  int  $id
     * @return User
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function searchUsersByFullName(string $text): Collection
    {
        $names = explode(' ', $text);

        return User::whereIn('first_name', $names)
            ->orWhereIn('middle_name', $names)
            ->orWhereIn('last_name', $names)->get();
    }

    public function save(User $user): User
    {
        $user->save();

        return $user;
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    public function getAll(): Collection
    {
        return User::query()->orderBy('id', 'asc')->get();
    }

    public function getUsersByHR(int $id): Collection
    {
        return User::query()->where([['manager_id', $id]])->get();
    }

    public function getWorkersList(): Collection
    {
        return User::query()->where([['role_id', User::WORKER_ROLE_ID]])->orderBy('manager_id', 'asc')->orderBy('last_name', 'asc')->get();
    }

    public function getHrsList(): Collection
    {
        return User::where([['role_id', User::HR_ROLE_ID]])->orderBy('last_name', 'asc')->get();
    }

    public function getAdmins(): Collection
    {
        return User::query()->where([['role_id', User::ADMIN_ROLE_ID]])->get();
    }
}
