<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="<?= site_url('client/dashboard') ?>" class="list-group-item list-group-item-action">Voir solde</a>
            <a href="<?= site_url('client/operation') ?>" class="list-group-item list-group-item-action">Nouvelle operation</a>
            <a href="<?= site_url('client/historique') ?>" class="list-group-item list-group-item-action">Historique</a>
            <a href="<?= site_url('client/profil') ?>" class="list-group-item list-group-item-action active">Mon profil</a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Mon profil</h4>

                <p>
                    Numero : <strong><?= esc($client['telephone']) ?></strong><br>
                    Statut :
                    <?php if ($client['statut'] === 'actif'): ?>
                        <span class="badge bg-success">Actif</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Suspendu</span>
                    <?php endif; ?>
                </p>
                <p class="text-muted small">Le statut du compte est gere par l'operateur.</p>

                <form method="post" action="<?= site_url('client/profil') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?= esc($client['nom']) ?>" placeholder="Votre nom" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
