<?php

namespace Hyyppa\Filemaker\Contracts;

interface SubcommandInterface
{
    /**
     * @param FilemakerModel     $model
     * @param FilemakerInterface $filemaker
     *
     * @return void
     */
    public static function execute(FilemakerModel $model, FilemakerInterface $filemaker): void;
}
