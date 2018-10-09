<?php namespace Hyyppa\Filemaker\Connector;

use Hyyppa\Filemaker\{
    Support\Config,
    Contracts\FilemakerModel,
    Contracts\PayloadInterface,
    Exception\CurlException,
    Support\FilemakerLog,
    Payload\FilePayload,
    Payload\Payload,
    Exception\FilemakerException,
    Contracts\QueryStringInterface
};

abstract class FilemakerConnector
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var resource
     */
    protected $curl;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var FilemakerLog
     */
    protected $log;


    /**
     * FilemakerConnector constructor.
     *
     * @param string|null $host
     * @param string|null $file
     * @param string|null $username
     * @param string|null $password
     *
     * @throws CurlException
     * @throws FilemakerException
     */
    public function __construct(
        string $host = null,
        string $file = null,
        string $username = null,
        string $password = null
    )
    {
        $this->config = new Config( [
            'host'              => $host ?? config( 'filemaker.host' ),
            'file'              => $file ?? config( 'filemaker.file' ),
            'username'          => $username ?? config( 'filemaker.username' ),
            'password'          => $password ?? config( 'filemaker.password' ),
            'ignore_ssl_errors' => config( 'filemaker.ignore-ssl-errors', true ),
        ] );

        $this->log = new FilemakerLog();

        $this->initCurl();
        $this->fetchNewToken();
    }


    /**
     *
     */
    public function __destruct()
    {
        $this->curlClose();
    }


    /**
     *
     */
    public function debugMode() : void
    {
        $this->log->enableDebug();
    }


    /**
     * @param string $path
     *
     * @return Response
     * @throws FilemakerException
     */
    protected function deleteRequest( string $path ) : Response
    {
        return $this->sendRequest( $path, 'DELETE' );
    }


    /**
     * @param string               $path
     * @param QueryStringInterface $query_string
     *
     * @return Response
     * @throws FilemakerException
     */
    protected function getRequest( string $path, QueryStringInterface $query_string = null ) : Response
    {
        return $this->sendRequest( $path, 'GET', null, $query_string );
    }


    /**
     * @param string               $path
     * @param Payload              $payload
     *
     * @param QueryStringInterface $query_string
     *
     * @return Response
     * @throws FilemakerException
     */
    protected function patchRequest( string $path, Payload $payload = null, QueryStringInterface $query_string = null ) : Response
    {
        return $this->sendRequest( $path, 'PATCH', $payload, $query_string );
    }


    /**
     * @param string               $path
     * @param Payload              $payload
     *
     * @param QueryStringInterface $query_string
     *
     * @return Response
     * @throws FilemakerException
     */
    protected function postRequest( string $path, Payload $payload = null, QueryStringInterface $query_string = null ) : Response
    {
        return $this->sendRequest( $path, 'POST', $payload, $query_string );
    }


    /**
     * @return string
     * @throws FilemakerException
     */
    protected function getToken() : string
    {
        return $this->config->token ?? $this->fetchNewToken();
    }


    /**
     * @param string                    $path
     *
     * @param QueryStringInterface|null $query_string
     *
     * @return string
     */
    protected function url( string $path = '', QueryStringInterface $query_string = null ) : string
    {
        $url = new UrlBuilder( $this->config );

        $url->append( $path );

        if( $query_string ) {
            $url->queryString( $query_string );
        }

        return (string)$url;
    }


    /**
     * @param Response $response
     *
     * @throws FilemakerException
     */
    protected function check( Response $response ) : void
    {
        if( $response->message( 'code' ) ) {
            throw new FilemakerException(
                $response->message( 'message' ),
                $response->message( 'code' )
            );
        }
    }


    /**
     * @param FilemakerModel|string $model
     *
     * @return string
     */
    protected function findUrl( $model ) : string
    {
        return '/layouts/' . $this->getTableName( $model ) . '/_find';
    }


    /**
     * @param FilemakerModel|string $model
     * @param string                $id
     *
     * @return string
     */
    protected function recordsUrl( $model, $id = '' ) : string
    {
        if( $id !== '' ) {
            $id = '/' . $id;
        }

        return '/layouts/' . $this->getTableName( $model ) . '/records' . $id;
    }


    /**
     * @param FilemakerModel|string $model
     * @param                       $field
     * @param string                $id
     *
     * @return string
     */
    protected function containerUrl( $model, $field, $id ) : string
    {
        return sprintf( '/layouts/%s/records/%s/containers/%s/1',
            $this->getTableName( $model ),
            $id,
            $field
        );
    }


    /**
     *
     * @throws CurlException
     */
    protected function initCurl() : void
    {
        $this->curl = curl_init();

        if( ! $this->curl ) {
            throw new CurlException( 'Curl could not be initialized.' );
        }

        if( $this->config->ignore_ssl_errors ) {
            curl_setopt( $this->curl, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt( $this->curl, CURLOPT_SSL_VERIFYPEER, 0 );
        }

        curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, true );
    }


    /**
     *
     */
    protected function curlClose() : void
    {
        curl_close( $this->curl );
        $this->curl = null;
    }


    /**
     * @param FilemakerModel|string $model
     *
     * @return string
     */
    protected function getTableName( $model ) : string
    {
        if( $model instanceof FilemakerModel ) {
            return $model->getFilemakerTable();
        }

        return with( new $model )->getFilemakerTable();
    }


    /**
     * @param string                    $path
     * @param string|null               $method
     * @param PayloadInterface|null     $payload
     *
     * @param QueryStringInterface|null $query_string
     *
     * @return Response
     * @throws FilemakerException
     */
    protected function sendRequest( string $path, string $method = 'POST', PayloadInterface $payload = null, QueryStringInterface $query_string = null ) : Response
    {
        $method  = strtoupper( $method );
        $header  = new Header( $this->config );
        $payload = $payload ?? new Payload();
        $url     = $this->url( $path, $query_string );

        if( $method === 'POST' || $method === 'PATCH' ) {

            if( $payload instanceof FilePayload ) {
                $header = new Header( $this->config, 'multipart/form-data' );
            }

            curl_setopt( $this->curl, CURLOPT_POSTFIELDS, $payload->toFilemaker() );
        } else {
            curl_setopt( $this->curl, CURLOPT_POSTFIELDS, false );
        }

        curl_setopt( $this->curl, CURLOPT_URL, $url );
        curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, $method );
        curl_setopt( $this->curl, CURLOPT_HTTPHEADER, $header->toArray() );

        $this->log->increment( 'api_calls' );

        if( ! $response = curl_exec( $this->curl ) ) {
            throw new FilemakerException( curl_error( $this->curl ), curl_errno( $this->curl ) );
        }

        try {
            $response = new Response( $response );

            $this->log->info( $url, $method, $header->toArray(), $payload->toFilemaker(), $response );

            $this->check( $response );
        }
        catch( FilemakerException $e ) {

            if( $e->getCode() === 952 ) {
                $this->fetchNewToken();

                return $this->sendRequest( $path, $method, $payload, $query_string );
            }

            if( \in_array( $e->getCode(), [
                FILEMAKER_RECORD_MISSING,
                FILEMAKER_NO_MATCH,
            ], true ) ) {
                return new Response();
            }

            throw $e;
        }

        return $response;
    }


    /**
     * @throws FilemakerException
     */
    protected function fetchNewToken() : string
    {
        $this->config->token = null;

        $token = $this->postRequest( 'sessions' )->dot( 'response.token' );

        $this->config->setToken( $token );

        return $this->config->token;
    }

}
