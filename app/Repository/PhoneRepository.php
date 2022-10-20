<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Phone;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class PhoneRepository
{
    public function create(array $fields): Phone
    {
        return Phone::create($fields);
    }

    /**
     * @param  int  $id
     * @return Phone
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Phone
    {
        return Phone::findOrFail($id);
    }

    public function getByUserId(int $user_id): array
    {
        return Phone::where([['user_id', $user_id]])->get()->toArray();
    }

    public function save(Phone $phone): Phone
    {
        $phone->save();

        return $phone;
    }

    public function delete(int $id): ?bool
    {
        return Phone::find($id)->delete();
    }
}
