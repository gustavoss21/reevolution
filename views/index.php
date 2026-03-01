<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link rel="icon" href="/favicon.ico"> -->

    <title><?php if (isset($title)) echo $title; ?></title>
    <!-- DEVELOP -->
    <!-- PRODUCTION -->
    <script src="https://unpkg.com/vue@3"></script>
    <!-- <link href="https://c.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> -->
</head>

<body>
    <?php echo $content; ?>
    
    <script type="module" data-component="<?php echo strtolower($title); ?>" src="http://localhost:5173/views/src/main.ts"></script>
    <!-- <script src="https://dn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script> -->
</body>

</html>