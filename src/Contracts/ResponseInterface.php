<?php

namespace Hyyppa\Filemaker\Contracts;

use Hyyppa\Filemaker\Connector\Response;

interface ResponseInterface
{
    /**
     * @return Response
     */
    public function recursive(): Response;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function message(string $key = '');

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function dot(string $key);
}
