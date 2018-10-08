<?php namespace Hyyppa\Filemaker\Contracts;

interface QueryStringInterface
{

    /**
     * @return string
     */
    public function queryString() : string;


    /**
     * @param $field
     *
     * @return $this
     */
    public function sortAscending( $field ) : QueryStringInterface;


    /**
     * @param $field
     *
     * @return $this
     */
    public function sortDescending( $field ) : QueryStringInterface;


    /**
     * @param int $limit
     *
     * @return $this
     */
    public function limit( int $limit ) : QueryStringInterface;


    /**
     * @param int $offset
     *
     * @return $this
     */
    public function offset( int $offset ) : QueryStringInterface;

}
