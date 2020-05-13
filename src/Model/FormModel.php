<?php

namespace Hyyppa\Filemaker\Model;

use Hyyppa\Filemaker\Contracts\FilemakerModel;
use Illuminate\Database\Eloquent\Model;

class FormModel extends Model implements FilemakerModel
{
    use FilemakerEloquentTrait;

    /**
     * @param string $table
     * @param array  $attributes
     *
     * @return FilemakerModel
     */
    public static function make(string $table, array $attributes): FilemakerModel
    {
        $record = new static();
        $record->setFilemakerTable($table);
        $record->setRawAttributes($attributes);

        return $record;
    }
}
