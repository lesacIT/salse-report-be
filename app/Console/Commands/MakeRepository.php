<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository';

    public function handle()
    {
        $name = $this->argument('name');

        $repositoryClass = ucfirst($name) . 'Repository';
        $fileName = app_path("Repositories/{$repositoryClass}.php");

        if (file_exists($fileName)) {
            $this->error('Repository already exists!');
            return;
        }

        $stub = file_get_contents(base_path('stubs/repository.stub'));

        $stub = str_replace('{{ RepositoryClass }}', $repositoryClass, $stub);

        file_put_contents($fileName, $stub);

        $this->info('Repository created successfully.');
    }
}
