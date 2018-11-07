<?php namespace Hyyppa\Filemaker\Model;

use Carbon\Carbon;
use Illuminate\Support\Collection;

use Hyyppa\Filemaker\{
    Contracts\FilemakerModel,
    Contracts\RecordInterface,
    Contracts\FilemakerInterface,
    Contracts\SubcommandInterface
};

trait FilemakerEloquentTrait
{

    public $fm_unique = [
        'id',
    ];

    public $fm_save = [
        //
    ];

    public $fm_load = [
        //
    ];

    public $fm_update = [
        //
    ];

    public $fm_table = '';


    /**
     * @param RecordInterface $record
     *
     * @return FilemakerModel
     */
    public static function fromRecord( RecordInterface $record ) : FilemakerModel
    {
        $attributes = array_filter( $record->toArray(), function ( $key ) {
            return ! ( strpos( $key, '::' ) !== false );
        }, ARRAY_FILTER_USE_KEY );

        $dates = with( new static )->getDates();

        foreach( $dates as $date ) {
            if( isset( $attributes[ $date ] ) && ! empty( $attributes[ $date ] ) ) {
                $attributes[ $date ] = Carbon::parse( $attributes[ $date ] )->format( 'Y-m-d H:i:s' ); //'m/d/Y H:i:s'
            } else {
                $attributes[ $date ] = null;
            }
        }

        return with( new static )->forceFill( $attributes );
    }


    /**
     * @return string
     */
    public static function tableName() : string
    {
        return with( new static )->getTable();
    }


    /**
     * @return Collection
     */
    public static function getFilemakerUnique() : Collection
    {
        return collect( with( new static )->fm_unique );
    }


    /**
     * @param int $count
     *
     * @return string
     */
    public static function friendlyName( $count = 1 ) : string
    {
        return str_replace(
            '_', ' ',
            str_plural(
                snake_case(
                    class_basename( static::class )
                ),
                $count
            )
        );
    }


    /**
     * @return string
     */
    public static function getFilemakerIndex() : string
    {
        return with( new static )->primaryKey;
    }


    /**
     * @param string $table
     */
    public function setFilemakerTable( string $table ) : void
    {
        $this->fm_table = $table;
    }


    /**
     * @return string
     */
    public function getFilemakerTable() : string
    {
        if( ! $this->fm_table ) {
            $this->fm_table = static::tableName();
        }

        return $this->fm_table;
    }


    /**
     * @param FilemakerInterface $filemaker
     */
    public function filemakerSave( FilemakerInterface $filemaker ) : void
    {
        /** @var SubcommandInterface $subcommand */
        foreach( $this->fm_save as $subcommand ) {
            $subcommand::execute( $this, $filemaker );
        }
    }


    /**
     * @param FilemakerInterface $filemaker
     */
    public function filemakerLoad( FilemakerInterface $filemaker ) : void
    {
        /** @var SubcommandInterface $subcommand */
        foreach( $this->fm_load as $subcommand ) {
            $subcommand::execute( $this, $filemaker );
        }
    }


    /**
     * @param FilemakerInterface $filemaker
     */
    public function filemakerUpdate( FilemakerInterface $filemaker ) : void
    {
        /** @var SubcommandInterface $subcommand */
        foreach( $this->fm_update as $subcommand ) {
            $subcommand::execute( $this, $filemaker );
        }
    }


    /**
     * @return array
     */
    public function queryString() : array
    {
        $query = collect( [] );

        foreach( $this->fm_unique as $key ) {
            $query->put( $key, (string)$this->attributes[ $key ] );
        }

        return $query->toArray();
    }


    /**
     * @param FilemakerModel $model
     *
     * @return bool
     */
    public function matches( $model ) : bool
    {
        return $this->toArray() === $model->toArray();
    }


    /**
     * @param array $fields
     *
     * @return mixed
     */
    public function toFilemaker( array $fields = [] )
    {
        $this->hidden = [];
        $attributes   = $this->attributes;
        $dates        = $this->getDates();

        foreach( $dates as $date ) {
            if( isset( $this->$date ) && ! empty( $this->$date ) ) {
                $carbon = new Carbon( $this->$date );
                if( $carbon->timestamp !== 0 ) {
                    $attributes[ $date ] = $carbon->format( 'm/d/Y H:i:s' );
                }
            } else {
                $attributes[ $date ] = null;
            }
        }

        foreach( $attributes as $key => $value ) {
            if( ! \in_array( $key, $fields, true ) ) {
                unset( $attributes[ $key ] );
                continue;
            }

            $attributes[ $key ] = (string)$attributes[ $key ];
        }

        return $attributes;
    }

}
