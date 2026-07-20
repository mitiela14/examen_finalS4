<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php $currentPage = 'profil'; ?>
<div class="row">
    <div class="col-md-3 mb-4">
        <?php echo $this->include('client/_sidebar', ['currentPage' => $currentPage]); ?>
    </div>

    <div class="col-md-9">
        <div class="mm-card fade-in-up">
            <div class="card-body p-4">
                <h4 class="fw-700 mb-2" style="color: var(--mm-dark);">
                    <i class="bi bi-person-gear text-primary"></i> Mon profil
                </h4>
                <p class="text-muted small mb-4">Gerez vos informations personnelles.</p>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3" style="background: var(--mm-light); border-radius: var(--mm-radius-sm);">
                            <div class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i> Numero</div>
                            <div class="fw-700 fs-5" style="color: var(--mm-dark);"><?= esc($client['telephone']) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background: var(--mm-light); border-radius: var(--mm-radius-sm);">
                            <div class="text-muted small mb-1"><i class="bi bi-shield-check me-1"></i> Statut</div>
                            <?php if ($client['statut'] === 'actif'): ?>
                                <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i> Actif</span>
                            <?php else: ?>
                                <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i> Suspendu</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="p-3 mb-4" style="background: #fffbeb; border-radius: var(--mm-radius-sm); border-left: 4px solid var(--mm-warning); font-size: 0.85rem;">
                    <i class="bi bi-info-circle text-warning me-1"></i>
                    Le statut du compte est gere par l'operateur. Vous ne pouvez modifier que votre nom.
                </div>

                <form method="post" action="<?= site_url('client/profil') ?>">
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
        </div>
    </div>
</div>

<?= $this->endSection() ?>
