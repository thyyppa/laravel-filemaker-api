<?php namespace Hyyppa\Filemaker\Contracts;

use Illuminate\Support\Collection;

interface FilemakerModel extends EloquentInterface
{

    /**
     * @param RecordInterface $record
     *
     * @return self
     */
    public static function fromRecord( RecordInterface $record ) : self;


    /**
     * @return string
     */
    public static function tableName() : string;


    /**
     * @return Collection
     */
    public static function getFilemakerUnique() : Collection;


    /**
     * @param int $count
     *
     * @return string
     */
    public static function friendlyName( $count = 1 ) : string;


    /**
     * @return string
     */
    public static function getFilemakerIndex() : string;


    /**
     * @param string $table
     */
    public function setFilemakerTable( string $table ) : void;


    /**
     * @return string
     */
    public function getFilemakerTable() : string;


    /**
     * @param FilemakerInterface $filemaker
     */
    public function filemakerSave( FilemakerInterface $filemaker ) : void;


    /**
     * @param FilemakerInterface $filemaker
     */
    public function filemakerLoad( FilemakerInterface $filemaker ) : void;


    /**
     * @param FilemakerInterface $filemaker
     */
    public function filemakerUpdate( FilemakerInterface $filemaker ) : void;


    /**
     * @return array
     */
    public function queryString() : array;


    /**
     * @param FilemakerModel $model
     *
     * @return bool
     */
    public function matches( $model ) : bool;


    /**
     * @param array $fields
     *
     * @return mixed
     */
    public function toFilemaker( array $fields = [] );


}
