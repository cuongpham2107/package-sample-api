
<?php

$namespace = 'VoyagerShoppingCart\Http\Controllers';


Route::group([
    'namespace' => $namespace,
    'prefix' => 'audit',
],function () {
    Route::get('/', function(){
        $array = [
            "https://i.dummyjson.com/data/products/10/1.jpg",
            "https://i.dummyjson.com/data/products/10/2.jpg",
            "https://i.dummyjson.com/data/products/10/3.jpg",
            "https://i.dummyjson.com/data/products/10/thumbnail.jpeg"
        ];
      echo implode(",", $array);
        // dd(__DIR__);
    });

    Route::get('test', function () {
        return "This is one more string route";
    });
});