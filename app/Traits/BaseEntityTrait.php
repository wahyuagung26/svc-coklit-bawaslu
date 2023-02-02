<?php

namespace App\Traits;

trait BaseEntityTrait
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->fillAttributes();
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

    private function fillAttributes()
    {
        foreach ($this->attributes as $key => $val) {
            $this->attributes[$key] = $this->payload[$key] ?? null;
        }
    }
}
