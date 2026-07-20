
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="<?= site_url('client/dashboard') ?>" class="list-group-item list-group-item-action active">Voir solde</a>
            <a href="<?= site_url('client/operation') ?>" class="list-group-item list-group-item-action">Nouvelle operation</a>
            <a href="<?= site_url('client/historique') ?>" class="list-group-item list-group-item-action">Historique</a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4>Bonjour, <?= esc($client['telephone']) ?></h4>
                <p class="text-muted">Voici les informations de votre compte.</p>

                <div class="p-4 bg-primary text-white rounded text-center">
                    <div class="small">Solde actuel</div>
                    <div class="display-6"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</div>
                </div>

                <a href="<?= site_url('client/operation') ?>" class="btn btn-primary mt-4">Faire une operation</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
