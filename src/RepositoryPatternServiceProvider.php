<?php

namespace Rafi021\RepositoryPattern;

use Rafi021\RepositoryPattern\Commands\MakeRepository;
use Rafi021\RepositoryPattern\Commands\MakeService;
use Rafi021\RepositoryPattern\Commands\ModelMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rafi021\RepositoryPattern\Commands\RepositoryPatternCommand;
use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;

class RepositoryPatternServiceProvider extends PackageServiceProvider
{

    public function register()
    {
        $this->registeringPackage();
        $this->package = new Package();

        $this->package->setBasePath($this->getPackageBaseDir());


        $this->configurePackage($this->package);

        if(empty($this->package->name)){
            throw InvalidPackage::nameIsRequired();
        }

        foreach ($this->package->configFileNames as $configFileName) {
            $this->mergeConfigFrom($this->package->basePath("/../config/{$configFileName}.php"), $configFileName);
        }

        $this->mergeConfigFrom(__DIR__ . "/../config/repository-pattern-sys.php", "repository-pattern");

        $this->packageRegistered();

        $this->overrideCommands();

        return $this;
    }


    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('repository-pattern')
            ->hasConfigFile()
            ->hasViews()
            // ->hasMigration('create_repository-pattern_table')
            // ->hasCommand(RepositoryPatternCommand::class);
            ->hasCommand(MakeRepository::class)
            ->hasCommand(MakeService::class);
    }

    public function overrideCommands()
    {
        $this->app->extend('command.model.make', function () {
            return app()->make(ModelMakeCommand::class);
        });
    }
}
