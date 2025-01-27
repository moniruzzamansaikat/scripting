<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('/images/favicon.jpg') ?>" type="image/x-icon">
    <title><?= $this->e($title ?? 'Default') ?></title>

    <link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/styles.css') ?>">
</head>

<body>
    <?= $this->insert('partials/navbar') ?>


    <div class="container">
        <?= $this->section('content') ?>
    </div>


    <script src="<?= asset('/js/bootstrap.bundle.min.js') ?>"></script>

    <?= $this->section('scripts') ?>
</body>

</html>