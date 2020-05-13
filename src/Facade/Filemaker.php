<?php

namespace Hyyppa\Filemaker\Facade;

use Carbon\Carbon;
use Hyyppa\Filemaker\Contracts\FilemakerInterface;
use Hyyppa\Filemaker\Contracts\FilemakerModel;
use Hyyppa\Filemaker\Model\ModelCollection;
use Hyyppa\Filemaker\Record\RecordCollection;
use Illuminate\Support\Facades\Facade;

/**
 * Class Filemaker.
 *
 * @method static RecordCollection      records( string $model, int $limit = 100 )
 * @method static ModelCollection       recordModels( string $model, int $limit = 100 )
 * @method static FilemakerModel|null   find( $model, $search )
 * @method static array                 query( $model, $search )
 * @method static FilemakerInterface    save( FilemakerModel $model )
 * @method static bool                  update( FilemakerModel $model, $search )
 * @method static bool                  needsUpdate( FilemakerModel $model )
 * @method static FilemakerModel        latest( $model )
 * @method static Carbon|null           lastUpdate( $model )
 * @method static void                  addFileToRecord( FilemakerModel $model, string $field, $filename )
 * @method static void                  debugMode()
 *
 * @see     \Hyyppa\Filemaker\FilemakerRepository
 */
class Filemaker extends Facade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return FilemakerInterface::class;
    }
}
