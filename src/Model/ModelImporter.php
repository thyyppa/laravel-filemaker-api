<?php

namespace Hyyppa\Filemaker\Model;

use Hyyppa\Filemaker\Contracts\FilemakerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\OutputStyle;

class ModelImporter
{
    /**
     * @var FilemakerInterface
     */
    protected $filemaker;

    /**
     * @var OutputStyle
     */
    protected $output;

    /**
     * @var InputInterface
     */
    protected $input;

    public function __construct(FilemakerInterface $filemaker)
    {
        $this->filemaker = $filemaker;
    }

    /**
     * @param OutputStyle $output
     *
     * @return ModelImporter
     */
    public function setOutput(OutputStyle $output): self
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @param InputInterface $input
     *
     * @return ModelImporter
     */
    public function setInput(InputInterface $input): self
    {
        $this->input = $input;

        return $this;
    }
}
