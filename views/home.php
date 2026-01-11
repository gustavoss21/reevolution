<?php
// include layout file
$layout = __DIR__ . '/layout/index.php';
$title = "Home Page";

$urlTimeline = $urlBase . '/timeline';

?>

<main class="container">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1>Analize de Evolução</h1>
        <p class="subtitle">Rumo à Transformação Digital</p>
    </div>

    <div>
        <div id="app">
            <HomeComponent></HomeComponent>
        </div>
</main>