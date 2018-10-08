<?php namespace Hyyppa\Filemaker\Connector;

use Illuminate\Support\Collection;
use Hyyppa\Filemaker\Support\Config;
use Hyyppa\Filemaker\Contracts\QueryStringInterface;
use Hyyppa\Filemaker\Contracts\UrlBuilderInterface;

class UrlBuilder extends Collection implements UrlBuilderInterface
{

    /**
     * @var Config
     */
    protected $config;


    public function __construct( Config $config )
    {
        $this->config = $config;

        Collection::__construct( [
            sprintf( 'https://%s/fmi/data/v1/databases/%s', $config->host, $config->file ),
        ] );
    }


    /**
     * @param mixed $value
     *
     * @return self
     */
    public function append( $value = null ) : self
    {
        if( $value ) {
            parent::push( str_replace_first( '/', '', $value ) );
        }

        return $this;
    }


    /**
     * @param QueryStringInterface $params
     */
    public function queryString( QueryStringInterface $params ) : void
    {
        $this->append( $params->queryString() );
    }


    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->implode( '/' );
    }
}
