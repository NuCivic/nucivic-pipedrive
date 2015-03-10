<?php

namespace NuCivicPipedrive\Console;

use Symfony\Component\Console\Application as BaseApplication;
use NucivicPipedrive\Console\Command\ExportCommand;

class Application extends BaseApplication
{
    const NAME = 'NuCivic PipeDrive Export Application';
    const VERSION = '1.0';

    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);
        $this->add(new ExportCommand());
    }
}
