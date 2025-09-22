<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($title)) echo $title; ?></title>
    <?php echo implode('\n',$static_links['top']) ?>
</head>

<body>
    <?php include_once dirname(__FILE__,2).'/partials/head.php' ?>
    <?php if (isset($content)) echo $content; ?>
    <?php echo implode('\n', $static_links['end']) ?>
</body>

</html>