<?php

namespace Hyyppa\Filemaker\Contracts;

interface PayloadInterface
{
    /**
     * @return int
     */
    public function length(): int;

    /**
     * @return string|array
     */
    public function toFilemaker();
}
