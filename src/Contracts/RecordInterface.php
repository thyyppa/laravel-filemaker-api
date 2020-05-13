<?php

namespace Hyyppa\Filemaker\Contracts;

interface RecordInterface
{
    /**
     * @return mixed
     */
    public function toArray();

    /**
     * @return FilemakerModel
     */
    public function toModel(): FilemakerModel;

    /**
     * @return FilemakerModel|string
     */
    public function getModel();

    /**
     * @return string
     */
    public function getIndex(): string;

    /**
     * @return array
     */
    public function fields(): array;
}
