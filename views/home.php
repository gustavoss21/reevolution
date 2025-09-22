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
?>


<div>
   <div>
       <h1>Home Page</h1>
       <p>Welcome to the home page!</p>
</div>
