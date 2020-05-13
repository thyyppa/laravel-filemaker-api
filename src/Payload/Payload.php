<?php

namespace Hyyppa\Filemaker\Payload;

use Hyyppa\Filemaker\Contracts\PayloadInterface;
use Illuminate\Support\Collection;

class Payload extends Collection implements PayloadInterface
{
    /**
     * @return int
     */
    public function length(): int
    {
        return \strlen($this->toFilemaker());
    }

    /**
     * @return string|array
     */
    public function toFilemaker()
    {
        return $this->toJson();
    }
}
