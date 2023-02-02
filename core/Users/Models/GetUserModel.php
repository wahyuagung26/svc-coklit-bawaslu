<?php

namespace Core\Users\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\Users\Entities\UsersResponseEntity;

class GetUserModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'm_user';
    protected $returnType = 'array';

    public function getUsers()
    {
        $this->select([
            'm_user.id',
            'm_user.name',
            'm_user.phone_number',
            'm_user.m_districts_id as district_id',
            'm_districts.district_name',
            'm_user.m_villages_id as village_id',
            'm_villages.village_name',
            'm_user.role',
            'm_user.last_login'
        ])->table('m_user')
        ->join('m_districts', 'm_user.m_districts_id = m_districts.id')
        ->join('m_villages', 'm_user.m_villages_id = m_villages.id', 'left')
        ->where('m_user.is_deleted', 0);

        return $this;
    }

    public function getByUsername(string $username, $exceptUserId = 0)
    {
        $query = $this->getUsers()->where('username', $username);
        if (!empty($exceptUserId)) {
            $query->where('m_user.id !=', $exceptUserId);
        }

        $user = $query->first();
        return $this->convertEntity(UsersResponseEntity::class, $user);
    }

    public function getById(int $userId)
    {
        $user = $this->getUsers()->find($userId);
        return $this->convertEntity(UsersResponseEntity::class, $user);
    }

    public function setFilter($payload)
    {
        if (isset($payload['name'])) {
            $this->like('m_villages.village_name', $payload['name']);
        }

        return $this;
    }

    public function all()
    {
        $users = $this->getResult();
        return $this->convertEntity(UsersResponseEntity::class, $users);
    }

    public function pagination($page = 1)
    {
        $limit = DEFAULT_PER_PAGE;
        $offset = DEFAULT_PER_PAGE * ($page - 1);

        $total = $this->countAllResults(false);
        $users = $this->limit($limit, $offset)->find();
        $users = $this->convertEntity(UsersResponseEntity::class, $users);
        return [
            'data'  => $users ?? [],
            'meta'  => [
                'total' => $total ?? 0,
                'page'  => 1,
                'per_page' => DEFAULT_PER_PAGE
            ]
        ];
    }
}
