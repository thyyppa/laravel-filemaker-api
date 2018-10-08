<?php namespace Hyyppa\Filemaker\Contracts;

interface ConfigInterface
{

    /**
     * @param string $token
     *
     * @return string
     */
    public function setToken( string $token ) : string;
}
