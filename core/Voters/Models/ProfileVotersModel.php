<?php

namespace Core\Voters\Models;

use App\Exceptions\ValidationException;

class ProfileVotersModel extends BaseVotersModel
{
    private $oldProfile;
    private $newProfile;
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

    public function setStatusUpdatedProfile()
    {
        $oldProfile = $this->oldProfile;
        $newProfile = $this->newProfile;
        $modifiedColumn = array_keys($newProfile);

        $this->isProfileUpdated = false;
        foreach ($modifiedColumn as $column) {
            if ($column == 'id') {
                continue;
            }

            $issetData = isset($oldProfile[$column]) && isset($newProfile[$column]);
            if ($issetData && $oldProfile[$column] != $newProfile[$column]) {
                $this->isProfileUpdated = true;
                break;
            }
        }

        return $this;
    }

    public function runUpdate()
    {
        $id = $this->newProfile['id'] ?? null;

        if (empty($id)) {
            throw new ValidationException('Id tidak boleh kosong', HTTP_STATUS_UNPROCESS);
        }

        if (empty($this->newProfile)) {
            throw new ValidationException('Data baru tidak boleh kosong', HTTP_STATUS_UNPROCESS);
        }

        $this->update($id, array_merge($this->newProfile, ['is_profile_updated' => $this->isProfileUpdated]));
        return $this;
    }

    public function setOldProfile(array $profile)
    {
        $this->oldProfile = $profile;
        return $this;
    }

    public function setNewProfile(array $profile)
    {
        $this->newProfile = $profile;
        return $this;
    }
}
