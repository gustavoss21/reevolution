<?php
// include layout file
$layout = dirname(__DIR__, 1) . '/layout/index.php';
$title = "Home Page";

$urlTimeline = $urlBase . '/timeline';

?>

<main class="container">
    <div>
        <div id="app">
            <home-component></home-component>
        </div>
</main>