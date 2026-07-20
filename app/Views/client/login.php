<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="mm-card fade-in-up">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 80px; height: 80px;">
                        <i class="bi bi-phone text-primary" style="font-size: 2.2rem;"></i>
                    </div>
                    <h4 class="fw-700 mt-3 mb-1" style="color: var(--mm-dark);">Connexion Client</h4>
                    <p class="text-muted small mb-0">Entrez votre numero Airtel (031) pour vous connecter.<br>Un compte sera cree automatiquement si necessaire.</p>
                </div>

                <form method="post" action="<?= site_url('client/login') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label">Numero de telephone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-primary"></i></span>
                            <input type="text" name="telephone" class="form-control form-control-lg border-start-0 ps-0" placeholder="0311234567" required style="font-size: 1.1rem;">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
                    </button>
                </form>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="<?= site_url('/') ?>" class="text-decoration-none" style="color: var(--mm-gray);">
                <i class="bi bi-arrow-left me-1"></i> Retour a l'accueil
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
