<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\EmergencyContact;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class EmergencyContactRepository
{
    public function create(array $fields): EmergencyContact
    {
        return EmergencyContact::create($fields);
    }

    /**
     * @param  int  $id
     * @return EmergencyContact
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): EmergencyContact
    {
        return EmergencyContact::findOrFail($id);
    }

    public function getByUserId(int $user_id)
    {
        return EmergencyContact::where([['user_id', $user_id]])->get()->toArray();
    }

    public function save(EmergencyContact $contact): EmergencyContact
    {
        $contact->save();

        return $contact;
    }

    public function delete($id): ?bool
    {
        return EmergencyContact::find($id)->delete();
    }
}
