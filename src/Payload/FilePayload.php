<?php namespace Hyyppa\Filemaker\Payload;

class FilePayload extends Payload
{

    /**
     * FilePayload constructor.
     *
     * @param string $filename
     * @param null   $data
     */
    public function __construct( string $filename, $data = null )
    {
        if( file_exists( $filename ) ) {
            $data = file_get_contents( $filename );
        }

        parent::__construct( [
            'source'   => $filename,
            'filename' => basename( $filename ),
            'data'     => $data ?? '',
        ] );
    }


    /**
     * {@inheritDoc}
     */
    public function toFilemaker()
    {
        return [
            'upload' => curl_file_create(
                $this->get( 'source' ),
                null,
                $this->get( 'filename' )
            ),
        ];
    }
}
