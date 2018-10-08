<?php namespace Hyyppa\Filemaker\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use Hyyppa\Filemaker\{
    Commands\ImportModelCommand,
    Contracts\FilemakerInterface,
    Facade\Filemaker,
    FilemakerRepository
};

class FilemakerServiceProvider extends ServiceProvider
{

    protected $artisan;


    /**
     *
     */
    public function boot() : void
    {
        $this->errorCodeDefinitions();

        $this->publishes( [
            __DIR__ . '/../../config/filemaker.php' => config_path( 'filemaker.php' ),
        ] );
    }


    /**
     *
     */
    public function register() : void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/filemaker.php', 'filemaker'
        );

        $this->app->bind( FilemakerInterface::class, function () {
            return new FilemakerRepository(
                config( 'filemaker.host' ),
                config( 'filemaker.file' ),
                config( 'filemaker.username' ),
                config( 'filemaker.password' )
            );
        } );

        AliasLoader::getInstance( [
            'Filemaker', Filemaker::class,
        ] )->register();

        $this->commands( [
            ImportModelCommand::class,
        ] );
    }


    /**
     * {@inheritDoc}
     */
    public function provides() : array
    {
        return [ FilemakerRepository::class, FilemakerInterface::class ];
    }


    /**
     *
     */
    protected function errorCodeDefinitions() : void
    {
        require_once __DIR__ . '/../Exception/FilemakerErrorCodes.php';
    }
}
