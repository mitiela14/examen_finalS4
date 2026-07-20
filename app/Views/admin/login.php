<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-3">Connexion Operateur</h4>

                <form method="post" action="<?= site_url('admin/login') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Code d'acces</label>
                        <input type="password" name="code_acces" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-secondary w-100">Se connecter</button>
                </form>

                <p class="text-muted small mt-3 mb-0">Code de test (base.sql) : <code>admin123</code></p>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="<?= site_url('/') ?>">&larr; Retour</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
