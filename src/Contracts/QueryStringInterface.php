<?php

namespace Hyyppa\Filemaker\Contracts;

interface QueryStringInterface
{
    /**
     * @return string
     */
    public function queryString(): string;

    /**
     * @param $field
     *
     * @return $this
     */
    public function sortAscending($field): self;

    /**
     * @param $field
     *
     * @return $this
     */
    public function sortDescending($field): self;

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): self;

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function offset(int $offset): self;
}
