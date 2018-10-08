<?php namespace Hyyppa\Filemaker\Contracts;

use Carbon\Carbon;
use Hyyppa\Filemaker\Exception\FilemakerException;
use Hyyppa\Filemaker\Model\ModelCollection;
use Hyyppa\Filemaker\Record\RecordCollection;

interface FilemakerInterface
{

    /**
     * @param string $model
     * @param int    $limit
     *
     * @return RecordCollection
     */
    public function records( string $model, int $limit = 100 ) : RecordCollection;


    /**
     * @param string|FilemakerModel $model
     * @param int                   $limit
     *
     * @return ModelCollection
     */
    public function recordModels( string $model, int $limit = 100 ) : ModelCollection;


    /**
     * @param FilemakerModel|string $model
     * @param                       $search
     *
     * @return FilemakerModel|null
     * @throws FilemakerException
     */
    public function find( $model, $search ) : ?FilemakerModel;


    /**
     * @param FilemakerModel|string $model
     * @param                       $search
     *
     * @return array
     * @throws FilemakerException
     */
    public function query( $model, $search ) : array;


    /**
     * @param FilemakerModel $model
     *
     * @return FilemakerInterface
     */
    public function save( FilemakerModel $model ) : FilemakerInterface;


    /**
     * @param FilemakerModel $model
     * @param                $search
     *
     * @return bool
     * @throws FilemakerException
     */
    public function update( FilemakerModel $model, $search ) : bool;


    /**
     * @param FilemakerModel $model
     *
     * @return bool
     * @throws FilemakerException
     */
    public function needsUpdate( FilemakerModel $model ) : bool;


    /**
     * @param string|FilemakerModel $model
     *
     * @throws FilemakerException
     *
     * @return FilemakerModel|null
     */
    public function latest( $model ) : ?FilemakerModel;


    /**
     * @param $model
     *
     * @return Carbon|null
     * @throws FilemakerException
     */
    public function lastUpdate( $model ) : ?Carbon;


    /**
     * @param FilemakerModel $model
     * @param string         $field
     * @param                $filename
     *
     * @throws FilemakerException
     */
    public function addFileToRecord( FilemakerModel $model, string $field, $filename );


    public function debugMode() : void;
}
