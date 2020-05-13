<?php

namespace Hyyppa\Filemaker\Record;

use Hyyppa\Filemaker\Contracts\FilemakerModel;
use Hyyppa\Filemaker\Contracts\RecordInterface;
use Illuminate\Support\Collection;

class Record extends Collection implements RecordInterface
{
    /**
     * @var string
     */
    public $model;

    public function __construct($items, $model)
    {
        if (! $items instanceof Collection) {
            $items = new Collection($items);
        }

        if ($items->has('fieldData')) {
            $items = $items->get('fieldData');
        }

        if ($model instanceof FilemakerModel) {
            $model = \get_class($model);
        }

        $this->model = $model;

        Collection::__construct($items);
    }

    /**
     * @return FilemakerModel
     */
    public function toModel(): FilemakerModel
    {
        return $this->model::fromRecord($this);
    }

    /**
     * @return FilemakerModel|string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        $index = $this->model::getFilemakerIndex();

        return (string) $this->get($index);
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return $this->items;
    }
}
