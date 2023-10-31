<?php
 
namespace CaLaravel\SampleApi\Http\Commands;
 

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;

class GenerateApiCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:api';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package supports developers to develop API quickly';
 
    /**
     * Execute the console command.
     */
    public function handle(): void
    { 
        
        $this->create_function_controller_api();
       
    }
    private function get_array_class_models() : array 
    {
        $modelsDirectory = app_path('Models');
        $files = File::files($modelsDirectory);
        $array = [];
        foreach ($files as $file) {
            $className = pathinfo($file, PATHINFO_FILENAME);
            array_push($array, $className);
        }
        return $array;
    }
    private function create_function_controller_api() : void 
    {
        $path = dirname(__DIR__);
        $array_models = $this->get_array_class_models();
        //create folder Api in Controllers
        $apiDirectory = app_path('Http/Controllers/Api');
        if (!File::exists($apiDirectory)) {
            File::makeDirectory($apiDirectory, 0755, true, true);
        }

        foreach ($array_models as $modelKey => $modelName) {

            $controllerName = $modelName . 'Controller';
            $namespace = config('sample-api.namespace');
            
            $modelNamePlural = strtolower(Str::plural($modelName));
        
            // Tạo Controller từ mẫu
            $controllerStub = File::get(__DIR__.'/../../stubs/controller.stub');
            
            //Relation
            $array_relations =  $this->check_relation_class('App\Models\\'.$modelName); //Product
            if($array_relations){
                $quotedArray = array_map(fn($item) => "'$item'", $array_relations);
                $relations = implode(', ', $quotedArray);
                // $test = 'test';
                $controllerContent = str_replace(
                    [
                        '{{Namespace}}', 
                        '{{ControllerName}}', 
                        '{{ModelName}}', 
                        '{{modelNamePlural}}',
                        '{{relations}}'
                    ],
                    [
                        $namespace, 
                        $controllerName, 
                        $modelName, 
                        $modelNamePlural,
                        $relations
                    ],
                    $controllerStub
                );
                
            }
            else{
                $controllerContent = str_replace(
                    [
                        '{{Namespace}}', 
                        '{{ControllerName}}', 
                        '{{ModelName}}', 
                        '{{modelNamePlural}}',
                        '{{relations}}'
                    ],
                    [
                        $namespace, 
                        $controllerName, 
                        $modelName, 
                        $modelNamePlural,
                        ''
                    ],
                    $controllerStub
                );
            }
            
            
            $controllerPath = app_path(config('sample-api.controller-api-path') . $controllerName . '.php');
            
            File::put($controllerPath, $controllerContent);

            $url = "api/".$modelNamePlural."";

            $exists = collect(\Route::getRoutes())->contains(function ($route) use ($url) {
                return $route->uri === $url;
            });

            if (!$exists) {
                // Định nghĩa routes API
                $apiRoutes = "Route::resource('" . $modelNamePlural . "'," . $namespace . '\\' . $controllerName . "::class);";
                // Kiểm tra nếu tệp api.php tồn tại
                if (File::exists(base_path('routes/api.php'))) {
                    // Kiểm tra nếu api.php không kết thúc bằng dòng mới, thêm một dòng mới
                    $content = File::get(base_path('routes/api.php'));
                    if (substr($content, -strlen(PHP_EOL)) !== PHP_EOL) {
                        $apiRoutes = PHP_EOL . $apiRoutes;
                    }
                }
          
                File::append(base_path('routes/api.php'), $apiRoutes);
                $this->info('Route API đã được tạo thành công!');
            }
            $this->info('Controller API đã được tạo thành công!');
          
        }
        
    }
    function check_relation_class($class) : array {

        $methods = get_class_methods($class);
        $relationships = [];
        foreach ($methods as $method) {
            $reflection = new \ReflectionMethod($class, $method);
            if ($reflection->getReturnType() instanceof \ReflectionNamedType) {
                $returnType = $reflection->getReturnType()->getName();
                if (is_subclass_of($returnType, Relation::class)) {
                    $relationships[] = $method;
                }
            }
        }
        return $relationships;
    }
   
}