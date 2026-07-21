<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php $currentPage = 'epargne'; ?>
<div class="row">   
    <div class="col-md-3 mb-4">
        <?php echo $this->include('client/_sidebar', ['currentPage' => $currentPage]); ?>
    </div>

    <div class="col-md-9">
        <div class="mm-card fade-in-up">
            <div class="card-body p-4">
                <h4 class="fw-700 mb-2" style="color: var(--mm-dark);">
                    <i class="bi bi-person-gear text-primary"></i> Mon compte epargne
                </h4>
                <p class="text-muted small mb-4">Gerez votre compte epargne.</p>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3" style="background: var(--mm-light); border-radius: var(--mm-radius-sm);">
                            <div class="text-muted small mb-1"><i class="bi bi-wallet2 me-1"></i> Solde epargne</div>
                            <div class="fw-700 fs-5" style="color: var(--mm-dark);"><?= number_format($Epargne['solde_epargne'], 0, ',', ' ') ?> Ar</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <form method="post" action="<?= site_url('client/addepargne') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-person me-1"></i> Nom complet</label>
                        <input type="text" name="nom" class="form-control form-control-lg" value="<?= esc($client['nom']) ?>" placeholder="Votre nom" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </form>
</div>

<?= $this->endSection() ?>