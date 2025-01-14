<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--r|repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $withRepository = $this->option('repository');

        $this->createService($name);

        if ($withRepository) {
            $this->createRepository($name);
            $this->createInterface($name);
        }
    }

    protected function createService($name)
    {
        $servicePath = app_path("Services/{$name}Service.php");
        $this->createFile($servicePath, $this->getServiceStub($name));
    }

    protected function createRepository($name)
    {
        $repositoryPath = app_path("Repositories/{$name}/{$name}Repository.php");
        $this->createFile($repositoryPath, $this->getRepositoryStub($name));
    }

    protected function createInterface($name)
    {
        $interfacePath = app_path("Repositories/{$name}/{$name}RepositoryInterface.php");
        $this->createFile($interfacePath, $this->getInterfaceStub($name));
    }

    protected function createFile($path, $content)
    {
        if (File::exists($path)) {
            $this->error("File {$path} already exists!");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->info("Created: {$path}");
    }

    protected function getServiceStub($name)
    {
        return "<?php\n\nnamespace App\Services;\n\nclass {$name}Service\n{\n    // \n}\n";
    }

    protected function getRepositoryStub($name)
    {
        return "<?php\n\nnamespace App\Repositories;\n\nuse App\Repositories\\{$name}RepositoryInterface;\n\nclass {$name}Repository implements {$name}RepositoryInterface\n{\n    // \n}\n";
    }

    protected function getInterfaceStub($name)
    {
        return "<?php\n\nnamespace App\Repositories;\n\ninterface {$name}RepositoryInterface\n{\n    // \n}\n";
    }
}
