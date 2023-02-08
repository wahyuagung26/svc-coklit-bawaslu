<?php

namespace Core\Voters\Models;

use Core\Voters\Entities\VotersEntity;

class ProfileVotersModel extends BaseVotersModel
{
    private $isProfileUpdated;
    protected $allowedFields = [
        'm_district_id',
        'm_village_id',
        'nkk',
        'nik',
        'name',
        'place_of_birth',
        'date_of_birth',
        'married_status',
        'gender',
        'address',
        'rt',
        'rw',
        'tps',
        'is_profile_updated'
    ];

    public function setStatusUpdatedProfile(VotersEntity $voterPreviousStatus, array $payload)
    {
        $oldProfile = $voterPreviousStatus->toArray();
        $newProfile = $payload;
        $modifiedColumn = array_keys($newProfile);

        $this->isProfileUpdated = false;
        foreach ($modifiedColumn as $column) {
            $issetData = isset($oldProfile[$column]) && isset($newProfile[$column]);
            if ($issetData && $oldProfile[$column] != $newProfile[$column]) {
                $this->isProfileUpdated = true;
                break;
            }
        }

        return $this;
    }

    public function runUpdate($id, array $data)
    {
        $this->update($id, array_merge($data, ['is_profile_updated' => $this->isProfileUpdated]));
        return $this;
    }
}
