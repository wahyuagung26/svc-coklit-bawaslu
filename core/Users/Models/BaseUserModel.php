<?php

namespace Core\Users\Models;

use App\Models\CoreModel;

class BaseUserModel extends CoreModel
{
    protected $primaryKey = "id";
    protected $table = 'm_user';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'phone_number', 'username', 'password', 'role', 'm_districts_id', 'm_villages_id', 'last_login', 'is_deleted', 'created_by', 'created_at', 'updated_by', 'updated_at'];
}
