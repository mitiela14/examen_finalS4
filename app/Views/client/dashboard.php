<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php $currentPage = 'dashboard'; ?>
<div class="row">
    <div class="col-md-3 mb-4">
        <?php echo $this->include('client/_sidebar', ['currentPage' => $currentPage]); ?>
    </div>

    <div class="col-md-9">
        <div class="mm-card mb-4 fade-in-up">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-700 mb-1" style="color: var(--mm-dark);">
                            Bonjour, <?= esc($client['nom'] !== '' ? $client['nom'] : $client['telephone']) ?>
                        </h4>
                        <p class="text-muted mb-0 small">Bienvenue sur votre espace Airtel Money</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bi bi-person-circle text-primary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>

                <?php if ($client['nom'] === ''): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-4" style="border-radius: var(--mm-radius-sm); border-left: 4px solid var(--mm-warning);">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            Vous n'avez pas encore renseigne votre nom.
                            <a href="<?= site_url('client/profil') ?>" class="alert-link fw-600">Completer mon profil</a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="solde-card text-center mb-4">
                    <div class="solde-label">Solde actuel</div>
                    <div class="solde-montant"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="<?= site_url('client/operation') ?>" class="text-decoration-none">
                            <div class="card border-0 text-center py-4" style="border-radius: var(--mm-radius); box-shadow: var(--mm-shadow); transition: var(--mm-transition);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--mm-shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--mm-shadow)'">
                                <i class="bi bi-plus-circle text-primary mb-2" style="font-size: 2rem;"></i>
                                <div class="fw-600" style="color: var(--mm-dark);">Nouvelle operation</div>
                                <small class="text-muted">Depot, retrait, transfert</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('client/historique') ?>" class="text-decoration-none">
                            <div class="card border-0 text-center py-4" style="border-radius: var(--mm-radius); box-shadow: var(--mm-shadow); transition: var(--mm-transition);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--mm-shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--mm-shadow)'">
                                <i class="bi bi-clock-history text-info mb-2" style="font-size: 2rem;"></i>
                                <div class="fw-600" style="color: var(--mm-dark);">Historique</div>
                                <small class="text-muted">Voir mes operations</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('client/profil') ?>" class="text-decoration-none">
                            <div class="card border-0 text-center py-4" style="border-radius: var(--mm-radius); box-shadow: var(--mm-shadow); transition: var(--mm-transition);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--mm-shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--mm-shadow)'">
                                <i class="bi bi-person-gear text-secondary mb-2" style="font-size: 2rem;"></i>
                                <div class="fw-600" style="color: var(--mm-dark);">Mon profil</div>
                                <small class="text-muted">Modifier mon nom</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('client/epargne') ?>" class="text-decoration-none">
                            <div class="card border-0 text-center py-4" style="border-radius: var(--mm-radius); box-shadow: var(--mm-shadow); transition: var(--mm-transition);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--mm-shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--mm-shadow)'">
                                <i class="bi bi-person-gear text-secondary mb-2" style="font-size: 2rem;"></i>
                                <div class="fw-600" style="color: var(--mm-dark);">Mon compte epargne</div>
                                <small class="text-muted">Voir mon solde</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
