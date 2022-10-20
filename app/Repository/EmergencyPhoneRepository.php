<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\EmergencyPhone;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class EmergencyPhoneRepository
{
    public function create(array $fields): EmergencyPhone
    {
        return EmergencyPhone::create($fields);
    }

    /**
     * @param  int  $id
     * @return EmergencyPhone
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): EmergencyPhone
    {
        return EmergencyPhone::findOrFail($id);
    }

    public function getByEmergencyContactId(int $id): array
    {
        return EmergencyPhone::where([['emergency_contact_id', $id]])->get()->toArray();
    }

    public function save(EmergencyPhone $EmergencyPhone): EmergencyPhone
    {
        $EmergencyPhone->save();

        return $EmergencyPhone;
    }

    public function delete(int $id): ?bool
    {
        return EmergencyPhone::find($id)->delete();
    }
}
