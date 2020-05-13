<?php

namespace Hyyppa\Filemaker\Payload;

class FieldPayload extends Payload
{
    /**
     * @return string
     */
    public function toFilemaker(): string
    {
        return json_encode([
            'fieldData' => $this->toArray(),
        ]);
    }
}
