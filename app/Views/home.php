<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 text-center">
        <h2 class="mb-4">Bienvenue</h2>
        <p class="text-muted mb-4">Choisissez votre espace pour continuer.</p>
        <div class="d-grid gap-3">
            <a href="<?= site_url('client/login') ?>" class="btn btn-primary btn-lg">Espace Client</a>
            <a href="<?= site_url('admin/login') ?>" class="btn btn-secondary btn-lg">Espace Operateur</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
