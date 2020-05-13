<?php

namespace Hyyppa\Filemaker\Payload;

use Hyyppa\Filemaker\Contracts\FilemakerModel;

class ModelPayload extends FieldPayload
{
    public function __construct(FilemakerModel $model, $fields = null)
    {
        parent::__construct($model->toFilemaker($fields));
    }
}
