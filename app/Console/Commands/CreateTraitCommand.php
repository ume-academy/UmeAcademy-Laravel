<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateTraitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->createTrait($name);
    }

    protected function createTrait($name)
    {
        $traitPath = app_path("Traits/{$name}Trait.php");
        $this->createFile($traitPath, $this->getTraitStub($name));
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

    protected function getTraitStub($name)
    {
        return "<?php\n\nnamespace App\Traits;\n\ntrait {$name}Trait \n{\n    // \n}\n";
    }
}
