<?php

namespace App\Traits;

trait BaseEntityTrait
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->syncOriginal();
        $this->fill($payload);
    }

    public function getFilledAtrributes()
    {
        foreach ($this->attributes as $key => $val) {
            if (isset($this->payload[$key]) && !is_null($this->payload[$key])) {
                $this->attributes[$key] = $this->payload[$key] ?? null;
            } else {
                unset($this->attributes[$key]);
            }
        }

        return $this->attributes;
    }

    public function fill(?array $data = null)
    {
        foreach ($this->attributes as $key => $val) {
            $this->__set($key, $data[$key] ?? null);
        }

        return $this;
    }
}
