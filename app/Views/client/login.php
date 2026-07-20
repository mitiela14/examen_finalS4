<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-3">Connexion Client</h4>
                <p class="text-muted small">Entrez votre numero. Si vous etes nouveau, un compte sera cree automatiquement.</p>

                <form method="post" action="<?= site_url('client/login') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Numero de telephone</label>
                        <input type="text" name="telephone" class="form-control" placeholder="0331234567" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="<?= site_url('/') ?>">&larr; Retour</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
