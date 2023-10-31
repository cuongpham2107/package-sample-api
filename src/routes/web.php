
<?php

$namespace = 'VoyagerShoppingCart\Http\Controllers';


Route::group([
    'namespace' => $namespace,
    'prefix' => 'audit',
],function () {
    Route::get('/', function(){
    //     $array = [
    //         "https://i.dummyjson.com/data/products/10/1.jpg",
    //         "https://i.dummyjson.com/data/products/10/2.jpg",
    //         "https://i.dummyjson.com/data/products/10/3.jpg",
    //         "https://i.dummyjson.com/data/products/10/thumbnail.jpeg"
    //     ];
    //   echo implode(",", $array);
    $model = 'products';
    
   
    
    $products = "api/".$model."";
 
    $exists = collect(\Route::getRoutes())->contains(function ($route) use ($products){
    
        return $route->uri === $products;
    });
    dd($exists);
        // dd(__DIR__);
    });

    Route::get('test', function () {
        return "This is one more string route";
    });
});