<?php namespace Hyyppa\Filemaker\Connector;

use Illuminate\Support\Collection;
use Hyyppa\Filemaker\Contracts\ResponseInterface;

class Response extends Collection implements ResponseInterface
{

    public function __construct( $items = null )
    {
        if( \is_string( $items ) ) {
            $items = json_decode( $items );
        }

        if( empty( $items ) ) {
            $items = [ 'response' => [ 'data' => [] ] ];
        }

        Collection::__construct( $items );
    }


    /**
     * @return Response
     */
    public function recursive() : Response
    {
        return $this->map( function ( $value ) {
            if( \is_array( $value ) || \is_object( $value ) ) {
                return ( new self( $value ) )->recursive();
            }

            return $value;
        } );
    }


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function message( string $key = '' )
    {
        if( ! $this->has( 'messages' ) ) {
            return 'no message';
        }

        $messages = $this->get( 'messages' )[ 0 ];

        if( $key ) {
            return $messages->$key;
        }

        return $messages;
    }


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function dot( string $key )
    {
        return data_get( $this->items, $key );
    }
}
