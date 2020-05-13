<?php

namespace Hyyppa\Filemaker\Connector;

use Hyyppa\Filemaker\Contracts\QueryStringInterface;
use Illuminate\Support\Collection;

class QueryString extends Collection implements QueryStringInterface
{
    /**
     * @var array
     */
    protected $sort = null;

    /**
     * @var int
     */
    protected $limit = null;

    /**
     * @var int
     */
    protected $offset = null;

    /**
     * @return string
     */
    public function queryString(): string
    {
        $query = [];

        $this->map(function ($value, $key) {
            return '_'.$key.'='.$value;
        });

        if ($this->offset !== null) {
            $query[] = '_offset='.$this->offset;
        }

        if ($this->limit !== null) {
            $query[] = '_limit='.$this->limit;
        }

        if ($this->sort !== null) {
            $query[] = '_sort='.json_encode($this->sort);
        }

        return '?'.implode('&', $query);
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function sortAscending($field): QueryStringInterface
    {
        $this->sort[] = ['fieldName' => $field, 'sortOrder' => 'ascend'];

        return $this;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function sortDescending($field): QueryStringInterface
    {
        $this->sort[] = ['fieldName' => $field, 'sortOrder' => 'descend'];

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): QueryStringInterface
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function offset(int $offset): QueryStringInterface
    {
        $this->offset = $offset;

        return $this;
    }
}
