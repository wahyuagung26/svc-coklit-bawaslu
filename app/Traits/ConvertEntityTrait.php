<?php

namespace App\Traits;

use ReflectionClass;

trait ConvertEntityTrait
{
    public function convertEntity($entityClass, &$data)
    {
        if (empty($data)) {
            return null;
        }

        if ($this->checkIsEntity($entityClass)) {
            if (isset($data[0])) {
                return $this->convertMultipleData($entityClass, $data);
            }
            return $this->convertSingleData($entityClass, $data);
        }

        return $data;
    }

    private function checkIsEntity($entityClass)
    {
        $reflection = new ReflectionClass($entityClass);
        $parent = $reflection->getParentClass()->name ?? '';

        return $parent == 'CodeIgniter\Entity\Entity' && $reflection->getMethod('setAttributes');
    }

    private function convertSingleData($entityClass, &$data)
    {
        $entity = new $entityClass($data);
        unset($data);
        return $entity->toArray();
    }

    private function convertMultipleData($entityClass, &$data)
    {
        foreach ($data as $val) {
            $model = new $entityClass($val);
            $entity[] = $model->toArray();
        }
        unset($data);
        return $entity;
    }
}
