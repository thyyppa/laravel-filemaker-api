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
        parent::__construct( [
            'source'   => $filename,
            'filename' => basename( $filename ),
            'data'     => $data ?? file_get_contents( $filename ),
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
