<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="mb-4">Tableau de bord Airtel Money</h4>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 bg-primary text-white rounded text-center">
                            <div class="small">Total general</div>
                            <div class="display-6 fw-bold"><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-success text-white rounded text-center">
                            <div class="small">Frais collectes</div>
                            <div class="display-6 fw-bold"><?= number_format($totalFrais, 0, ',', ' ') ?> Ar</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-warning text-dark rounded text-center">
                            <div class="small">Commissions inter-op</div>
                            <div class="display-6 fw-bold"><?= number_format($totalCommission, 0, ',', ' ') ?> Ar</div>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">Gains par type d'operation</h5>
                <?php if (empty($gains)): ?>
                    <p class="text-muted">Aucune operation enregistree.</p>
                <?php else: ?>
                    <table class="table table-striped mb-4">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-end">Frais</th>
                                <th class="text-end">Commission</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gains as $g): ?>
                                <tr>
                                    <td><?= esc(ucfirst($g['type_operation'])) ?></td>
                                    <td class="text-center"><?= esc($g['nombre']) ?></td>
                                    <td class="text-end"><?= number_format($g['total_frais'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end"><?= number_format($g['total_commission'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end fw-bold"><?= number_format($g['total_frais'] + $g['total_commission'], 0, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <h5 class="mb-3">Gains par operateur destinataire</h5>
                <?php if (empty($gainsParOperateur)): ?>
                    <p class="text-muted">Aucun transfert inter-operateur.</p>
                <?php else: ?>
                    <table class="table table-striped mb-4">
                        <thead>
                            <tr>
                                <th>Operateur</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-end">Frais</th>
                                <th class="text-end">Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gainsParOperateur as $g): ?>
                                <tr>
                                    <td>
                                        <?php if ($g['operateur_destinataire'] === 'airtel'): ?>
                                            <span class="badge bg-primary">Airtel (notre)</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><?= esc(ucfirst($g['operateur_destinataire'])) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= esc($g['nombre']) ?></td>
                                    <td class="text-end"><?= number_format($g['total_frais'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end"><?= number_format($g['total_commission'], 0, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <h5 class="mb-3">Soldes des operateurs (dettes inter-op)</h5>
                <div class="row g-3">
                    <?php foreach ($soldesOperateurs as $s): ?>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="small text-muted mb-1">
                                        <?php if ($s['operateur'] === 'airtel'): ?>
                                            Airtel (notre operateur)
                                        <?php else: ?>
                                            <?= esc(ucfirst($s['operateur'])) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="fw-bold" style="font-size: 1.2rem; color: <?= $s['montant_a_envoyer'] > 0 ? 'var(--mm-warning)' : 'var(--mm-success)' ?>;">
                                        <?= number_format($s['montant_a_envoyer'], 0, ',', ' ') ?> Ar
                                    </div>
                                    <div class="small text-muted">a envoyer</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
