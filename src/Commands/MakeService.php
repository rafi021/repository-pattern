<?php

namespace Rafi021\RepositoryPattern\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Rafi021\RepositoryPattern\AssistCommand;
use Rafi021\RepositoryPattern\CreateFile;

class MakeService extends Command
{
    use AssistCommand;

    public $signature = 'make:service
        {name : The name of the service }';

    public $description = 'Create a new service class';

    public function handle()
    {
        $name = str_replace(config("repository-pattern.service_suffix"), "", $this->argument("name"));
        $className = Str::studly($name);

        $this->checkIfRequiredDirectoriesExist();

        $this->createService($className);
    }

    /**
     * Create the service
     *
     * @param string $className
     * @return void
     */
    public function createService(string $className)
    {
        $serviceName = $className . config("repository-pattern.service_suffix");
        $stubProperties = [
            "{namespace}" => config("repository-pattern.service_namespace"),
            "{serviceName}" => $serviceName,
            "{repositoryInterface}" => $this->getRepositoryInterfaceName($className),
            "{repositoryInterfaceNamespace}" => $this->getRepositoryInterfaceNamespace($className),
        ];

        new CreateFile(
            $stubProperties,
            $this->getServicePath($className),
            __DIR__ . "/stubs/service.stub"
        );


        $this->line("<info>Created service:</info> {$serviceName}");
    }

    /**
     * Get service path
     *
     * @return string
     */
    private function getServicePath($className)
    {
        return $this->appPath() . "/" .
            config("repository-pattern.service_directory") .
            "/$className" . "Service.php";
    }

    /**
     * Get repository interface namespace
     *
     * @return string
     */
    private function getRepositoryInterfaceNamespace(string $className)
    {
        return config("repository-pattern.repository_namespace") . "\Interfaces";
    }

    /**
     * Get repository interface name
     *
     * @return string
     */
    private function getRepositoryInterfaceName(string $className)
    {
        return $className . "RepositoryInterface";
    }

    /**
     * Check to make sure if all required directories are available
     *
     * @return void
     */
    private function checkIfRequiredDirectoriesExist()
    {
        $this->ensureDirectoryExists(config("repository-pattern.service_directory"));
    }
}
