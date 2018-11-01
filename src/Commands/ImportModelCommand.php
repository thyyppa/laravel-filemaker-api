<?php namespace Hyyppa\Filemaker\Commands;

use Illuminate\{
    Console\Command,
    Support\Collection,
    Database\Eloquent\Model,
    Console\Scheduling\Schedule,
    Contracts\Foundation\Application
};

use Hyyppa\Filemaker\{
    Contracts\FilemakerModel,
    Facade\Filemaker,
    Contracts\FilemakerInterface
};

class ImportModelCommand extends Command
{

    public $signature = 'model:import {model} {--all} {--last=500}';

    public $description = 'Import Eloquent model to Filemaker';

    /**
     * @var FilemakerInterface
     */
    protected $filemaker;

    /**
     * @var FilemakerModel|string
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;


    public function __construct( Application $app )
    {
        $this->app = $app;

        parent::__construct();
    }


    /**
     * @param FilemakerInterface $filemaker
     *
     * @throws \Hyyppa\Filemaker\Exception\FilemakerException
     */
    public function handle( FilemakerInterface $filemaker ) : void
    {
        $this->filemaker = $filemaker;

        if( $this->output->isVerbose() ) {
            $this->filemaker->debugMode();
        }

        $this->model = $this->modelClassname();
        $needsUpdate = $this->getModelsNeedingUpdate();

        $this->output->newLine();
        $count = $needsUpdate->count();
        $this->info( sprintf(
            'Importing %d %s',
            $count,
            $this->model::friendlyName( $count )
        ) );
        $this->output->progressStart( $count );

        $needsUpdate->each( function ( Model $model ) {
            $this->filemaker->save( $model );
            $this->output->progressAdvance();
        } );

        $this->output->progressFinish();
    }


    /**
     * @param Schedule $schedule
     */
    public function schedule( Schedule $schedule ) : void
    {
        // $schedule->command(static::class)->everyMinute();
    }


    /**
     * @return string|FilemakerModel
     */
    protected function modelClassname() : string
    {
        $model = '';

        if( ! str_contains( $this->argument( 'model' ), $this->app->getNamespace() ) ) {
            $model = $this->app->getNamespace();
        }

        $model .= str_replace( '/', '\\',
            studly_case( $this->argument( 'model' ) )
        );

        return $model;
    }


    /**
     * @return Collection
     * @throws \Hyyppa\Filemaker\Exception\FilemakerException
     */
    protected function getModelsNeedingUpdate() : Collection
    {
        $latest = $this->filemaker->latest( $this->model );

        if( $latest === null || $this->option( 'all' ) ) {
            return with( new $this->model )->all();
        }

        if( $latest === null || $this->option( 'last' ) ) {
            return with( new $this->model )->orderBy( 'created_at', 'desc' )->limit( $this->option( 'last' ) )->get();
        }

        /** @var Collection $needsUpdate */
        $needsUpdate = with( new $this->model )->where(
            'updated_at', '>=', $latest->updated_at
        )->get();

        $needsUpdate->shift();

        return $needsUpdate;
    }
}
