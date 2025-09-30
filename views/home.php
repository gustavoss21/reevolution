<?php
// include layout file
$layout = __DIR__ . '/layout/index.php';
$title = "Home Page";
$static_links = [
    'top' => [
        'style' => [
            'style.css'
        ]
    ],
    'end' => [
        'script' => [
            'script.js'
        ]
    ]
];
// $uri = 

$urlTimeline = $urlBase . '/timeline';

$request = new Request($urlTimeline, ['method' => 'GET']);
$timeline_data = json_decode($request->run());
?>

<main class="container">
    <h1>Analize de Evolução</h1>
    <div class="block-container">
        <?php foreach ($timeline_data as $theme) { ?>
            <a href="temas/<?= $theme->id ?>">

                <div class="theme-item" id="<?= $theme->slug ?>">
                    <h3 class="theme-title"><?= $theme->name ?></h3>
                    <h4 class="theme-topic"><?= $theme->topic->name ?></h4>
                    <div>
                        <span class="title-item">Estagio:</span>
                        <?= $theme->topic->stage->name ?>
                    </div>
                    <div>
                        <span class="title-item">Prioridade:</span>
                        <?= $theme->topic->stage->priority ?>
                    </div>
                    <div>
                        <span class="title-item">Domínio:</span>
                        <?= $theme->topic->stage->domain_level ?>
                    </div>
                    <div>
                        <span class="title-item">Estatus:</span>
                        <?= $theme->topic->stage->status ?>
                    </div>
                    <div>
                        <span class="title-item">Pontuação:</span>
                        <?= $theme->topic->stage->pontuacao ?>
                    </div>
                    <div>
                        <span class="title-item">Ultima atualização: </span>
                        <data><?= $theme->topic->stage->updated_at ?></data>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</main>