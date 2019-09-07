<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class CRUDGeneratorCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud{entity : Entity name to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automaticly create CRUD entity for admin.';

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //start
        //get parameter
        $entityName = "";
        try{
            $this->info("checking entity name...");
            $entityName = $this->getEntityName();

            //create controller
            $this->makeDirectory($this->createControllerName($entityName));
            $this->files->put($this->getControllersPath(), $this->buildClass($entityName));
        }catch(\Exception $ex){
            $this->info("Failed to create");
            $this->error($ex->getMessage());
        }
    }

    private function createControllerName($entityName){
        return ucwords($entityName).'Controller.php';
    }

    private function createModelName($entityName){
        return ucwords($entityName).'.php';
    }

    private function getEntityName()
    {
        $entityName = $this->argument('entity');

        if ($entityName == null || $entityName=="") {
            throw new \Exception("Invalid Entity Name '$entityName'");
        }

        //generate controller name
        $controllerName = $this->createControllerName($entityName);
        $this->info('- '.$controllerName.' checking..');
        //check controller exist
        $isControllerExist = $this->files->exists($this->getControllersPath().$controllerName);
        if($isControllerExist){
            throw new \Exception("Controller for '$entityName' already exist!");
        }else{
            $this->info("  ".$controllerName." OK!");
        }

        //check model
        $modelName = $this->createModelName($entityName);
        $this->info('- '.$modelName.' checking..');
        //check model exist
        $isModelExist = $this->files->exists($this->getModelsPath().$modelName);
        if($isModelExist){
            throw new \Exception("Model for '$entityName' already exist!");
        }else{
            $this->info("  ".$modelName." OK!");
        }

        return $entityName;
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name);
    }

    private function getControllersPath(){
        return $this->getPath('Http/Controllers/Admin/');
    }

    private function getModelsPath(){
        return $this->getPath('Models/');
    }
}
