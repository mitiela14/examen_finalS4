<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="text-center py-5 fade-in-up" style="min-height: 70vh; display: flex; align-items: center; justify-content: center;">
    <div>
        <div style="font-size: 5rem; margin-bottom: 1rem;">
            <i class="bi bi-phone-vibrate text-primary"></i>
        </div>
        <h1 class="fw-800 mb-3" style="font-size: 2.5rem; letter-spacing: -0.03em; color: var(--mm-dark);">
            Airtel Money
        </h1>
        <p class="text-muted mb-5" style="font-size: 1.1rem; max-width: 450px; margin: 0 auto;">
            Envoyez et recevez de l'argent facilement via Airtel, en toute securite.
        </p>
        <div class="d-grid gap-3 mx-auto" style="max-width: 360px;">
            <a href="<?= site_url('client/login') ?>" class="btn btn-primary btn-lg py-3">
                <i class="bi bi-person-fill me-2"></i> Espace Client
            </a>
            <a href="<?= site_url('admin/login') ?>" class="btn btn-dark btn-lg py-3">
                <i class="bi bi-shield-lock-fill me-2"></i> Espace Operateur
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
