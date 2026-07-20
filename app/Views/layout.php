<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'VINA-AKOHO Mobile Money' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">VINA-AKOHO Mobile Money</span>
        <?php if (session()->get('isClientLoggedIn')): ?>
            <a href="<?= site_url('client/logout') ?>" class="btn btn-outline-light btn-sm">Deconnexion</a>
        <?php elseif (session()->get('isAdminLoggedIn')): ?>
            <a href="<?= site_url('admin/logout') ?>" class="btn btn-outline-light btn-sm">Deconnexion</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container pb-5">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
