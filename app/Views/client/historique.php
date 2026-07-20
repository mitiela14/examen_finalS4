<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php $currentPage = 'historique'; ?>
<div class="row">
    <div class="col-md-3 mb-4">
        <?php echo $this->include('client/_sidebar', ['currentPage' => $currentPage]); ?>
    </div>

    <div class="col-md-9">
        <div class="mm-card fade-in-up">
            <div class="card-body p-4">
                <h4 class="fw-700 mb-2" style="color: var(--mm-dark);">
                    <i class="bi bi-clock-history text-primary"></i> Historique des operations
                </h4>
                <p class="text-muted small mb-4">Toutes vos operations passees.</p>

                <?php if (empty($historique)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Aucune operation pour le moment.</p>
                        <a href="<?= site_url('client/operation') ?>" class="btn btn-primary btn-sm mt-3">
                            <i class="bi bi-plus-circle me-1"></i> Faire une operation
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover" style="font-size: 0.85rem;">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">Frais</th>
                                    <th class="text-end">Commission</th>
                                    <th>Destinataire</th>
                                    <th>Operateur</th>
                                    <th class="text-end">Recu</th>
                                    <th class="text-end">Debite</th>
                                    <th class="text-end">Solde</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique as $h): ?>
                                    <tr>
                                        <td class="text-nowrap">
                                            <small class="text-muted"><?= esc(substr($h['date_operation'], 0, 16)) ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-primary';
                                            $icon = 'bi-send';
                                            if ($h['type_libelle'] === 'depot') { $badgeClass = 'bg-success'; $icon = 'bi-arrow-down-circle'; }
                                            elseif ($h['type_libelle'] === 'retrait') { $badgeClass = 'bg-warning text-dark'; $icon = 'bi-arrow-up-circle'; }
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <i class="bi <?= $icon ?> me-1"></i><?= esc(ucfirst($h['type_libelle'])) ?>
                                            </span>
                                            <?php if (!empty($h['est_envoi_multiple'])): ?>
                                                <span class="badge bg-info"><i class="bi bi-collection"></i></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end fw-600"><?= number_format($h['montant'], 0, ',', ' ') ?></td>
                                        <td class="text-end" style="color: var(--mm-danger);"><?= number_format($h['frais'], 0, ',', ' ') ?></td>
                                        <td class="text-end" style="color: var(--mm-danger);"><?= number_format($h['commission'] ?? 0, 0, ',', ' ') ?></td>
                                        <td>
                                            <?php if (!empty($h['telephone_destinataire'])): ?>
                                                <code class="small"><?= esc($h['telephone_destinataire']) ?></code>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($h['operateur_destinataire'])): ?>
                                                <?php if ($h['operateur_destinataire'] === 'airtel'): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success"><?= esc($h['operateur_destinataire']) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning"><?= esc($h['operateur_destinataire']) ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end fw-600" style="color: var(--mm-success);"><?= number_format($h['montant_recu'] ?? 0, 0, ',', ' ') ?></td>
                                        <td class="text-end fw-700" style="color: var(--mm-primary);"><?= number_format($h['montant_debit'] ?? 0, 0, ',', ' ') ?></td>
                                        <td class="text-end fw-600" style="color: var(--mm-success);"><?= number_format($h['solde_apres'], 0, ',', ' ') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
