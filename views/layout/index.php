<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico">

    <title><?php if (isset($title)) echo $title; ?></title>
    <!-- DEVELOP -->
    <!-- PRODUCTION -->
    <script src="https://unpkg.com/vue@3"></script>
    <?php echo implode('\n', STATIC_LINKS['top']) ?>
</head>

<body>
    <?php include_once dirname(__FILE__, 2) . '/partials/head.php' ?>

    <?php echo $content; ?>

    <script type="module" src="http://localhost:5173/views/static/js/main.js"></script>
    <?php echo implode('\n', STATIC_LINKS['end']) ?>
</body>

</html>