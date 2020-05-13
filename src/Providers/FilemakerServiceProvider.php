<?php

namespace Hyyppa\Filemaker\Providers;

use Hyyppa\Filemaker\Commands\ImportModelCommand;
use Hyyppa\Filemaker\Contracts\FilemakerInterface;
use Hyyppa\Filemaker\Facade\Filemaker;
use Hyyppa\Filemaker\FilemakerRepository;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class FilemakerServiceProvider extends ServiceProvider
{
    protected $artisan;

    public function boot(): void
    {
        $this->errorCodeDefinitions();

        $this->publishes([
            __DIR__.'/../../config/filemaker.php' => config_path('filemaker.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/filemaker.php', 'filemaker'
        );

        $this->app->bind(FilemakerInterface::class, function () {
            return new FilemakerRepository(
                config('filemaker.host'),
                config('filemaker.file'),
                config('filemaker.username'),
                config('filemaker.password')
            );
        });

        AliasLoader::getInstance([
            'Filemaker', Filemaker::class,
        ])->register();

        $this->commands([
            ImportModelCommand::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function provides(): array
    {
        return [FilemakerRepository::class, FilemakerInterface::class];
    }

    protected function errorCodeDefinitions(): void
    {
        require_once __DIR__.'/../Exception/FilemakerErrorCodes.php';
    }
}
