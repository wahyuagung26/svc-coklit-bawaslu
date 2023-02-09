<?php

namespace App\Traits;

use ReflectionClass;

trait ConvertEntityTrait
{
    public function convertEntity($entityClass, $data)
    {
        if (empty($data)) {
            return null;
        }

        if (!$this->checkIsEntity($entityClass)) {
            return $data;
        }

        if (isset($data[0])) {
            $data = $this->convertMultipleData($entityClass, $data);
        } else {
            $data = $this->convertSingleData($entityClass, $data);
        }

        return $data;
    }

    private function checkIsEntity($entityClass)
    {
        $reflection = new ReflectionClass($entityClass);
        return $reflection->getMethod('setAttributes');
    }

    private function convertSingleData($entityClass, &$data)
    {
        $entity = new $entityClass($data);
        unset($data);
        return $entity;
    }

    private function convertMultipleData($entityClass, &$data)
    {
        foreach ($data as $val) {
            $model = new $entityClass($val);
            $entity[] = $model;
        }
        unset($data);
        return $entity;
    }
}
