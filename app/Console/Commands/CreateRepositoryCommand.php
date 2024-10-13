<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class and repository interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->createRepository($name);
        $this->createRepositoryInterface($name);
    }

    protected function createRepository($name)
    {
        $repositoryPath = app_path("Repositories/{$name}/{$name}Repository.php");
        $this->createFile($repositoryPath, $this->getRepositoryStub($name));
    }

    protected function createRepositoryInterface($name)
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

    protected function getRepositoryStub($name)
    {
        return "<?php\n\nnamespace App\Repositories\\$name;\n\nuse App\Repositories\\$name\\{$name}RepositoryInterface;\n\nclass {$name}Repository implements {$name}RepositoryInterface\n{\n    // \n}\n";
    }

    protected function getInterfaceStub($name)
    {
        return "<?php\n\nnamespace App\Repositories\\$name;\n\ninterface {$name}RepositoryInterface\n{\n    // \n}\n";
    }
}
