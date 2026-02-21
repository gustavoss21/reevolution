<?php
// include layout file
$layout = dirname(__DIR__, 1) . '/layout/index.php';
$title = "Acompanhamento";
?>

<main class="container">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1>Acompanhamento</h1>
    </div>

    <div>
        <div id="app">
            <accompaniment-component></accompaniment-component>
        </div>
    </div>
</main>