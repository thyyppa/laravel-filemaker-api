<?php namespace Hyyppa\Filemaker\Connector;

use Illuminate\Support\Collection;
use Hyyppa\Filemaker\Support\Config;

class Header extends Collection
{

    /**
     * @var Config
     */
    protected $config;


    public function __construct( Config $config, string $type = 'application/json' )
    {
        $this->config = $config;

        Collection::__construct( [
            $this->authHeader(),
            'Content-Type: ' . $type,
        ] );
    }


    /**
     * @return string
     */
    protected function authHeader() : string
    {
        if( $this->config->token ) {
            $type = 'Bearer';
            $auth = $this->config->token;
        } else {
            $type = 'Basic';
            $auth = base64_encode( $this->config->username . ':' . $this->config->password );
        }

        return sprintf( 'Authorization: %s %s', $type, $auth );
    }
}
