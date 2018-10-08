<?php namespace Hyyppa\Filemaker\Payload;

use Illuminate\Support\Collection;

class SearchPayload extends Payload
{

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    protected $sort = [];


    /**
     * SearchPayload constructor.
     *
     * @param array $query
     * @param array $sort
     */
    public function __construct( $query = [], $sort = [] )
    {
        Collection::__construct( [] );

        $this->query = $query;
        $this->sort  = $sort;
    }


    /**
     * @return string
     */
    public function toFilemaker() : string
    {
        $output = [];

        if( ! empty( $this->query ) ) {
            $output[ 'query' ] = [ $this->getQuery() ];
        }

        if( ! empty( $this->sort ) ) {
            $output[ 'sort' ] = $this->getSort();
        }

        return json_encode( $output );
    }


    /**
     * @return array
     */
    public function getSort() : array
    {
        return $this->sort;
    }


    /**
     * @param array $sort
     *
     * @return Payload
     */
    public function setSort( array $sort ) : Payload
    {
        $this->sort = $sort;

        return $this;
    }


    /**
     * @param $field
     *
     * @return Payload
     */
    public function sortAscending( $field ) : Payload
    {
        $this->sort[] = [
            'fieldName' => $field,
            'sortOrder' => 'ascend',
        ];

        return $this;
    }


    /**
     * @param $field
     *
     * @return Payload
     */
    public function sortDescending( $field ) : Payload
    {
        $this->sort[] = [
            'fieldName' => $field,
            'sortOrder' => 'descend',
        ];

        return $this;
    }


    /**
     * @return array
     */
    public function getQuery() : array
    {
        return $this->query;
    }


    /**
     * @param array $query
     *
     * @return Payload
     */
    public function setQuery( array $query ) : Payload
    {
        $this->query = $query;

        return $this;
    }
}
