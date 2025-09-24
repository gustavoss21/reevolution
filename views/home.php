<?php
// include layout file
$layout = __DIR__ . '/layout/index.php';
$title = "Home Page";
$static_links = [
    'top'=>[
        'style' => [
            'style.css'
            ]
        ],
    'end' =>[
        'script' => [
            'script.js'
            ]
    ]
];
// $uri = 
$urlBase = $_SERVER['SERVER_NAME'] . '/reevolution';
$urlTimeline = $urlBase . '/timeline';

$request = new Request($urlTimeline,['method'=>'GET']);
$request->run()
?>


<div class="container">
   <div>
       <h1>Home Page</h1>
       <p>Welcome to the home page!</p>
        <? echo $url ?>
    
</div>
