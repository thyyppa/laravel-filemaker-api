<?php

namespace Hyyppa\Filemaker\Record;

use Hyyppa\Filemaker\Contracts\FilemakerModel;
use Illuminate\Support\Collection;

class RecordCollection extends Collection
{
    /**
     * @var FilemakerModel
     */
    protected $model;

    public function __construct($model, $records = [])
    {
        $this->model = $model;

        Collection::__construct([]);

        foreach ($records as $record) {
            $new = new Record($record, $model);

            $this->push($new);
        }
    }
}
