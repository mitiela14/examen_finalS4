<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4>Client : <?= esc($client['telephone']) ?></h4>
                <p>Solde actuel : <strong><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong></p>
                <p class="text-muted">Compte cree le <?= esc($client['date_creation']) ?></p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Historique des operations</h5>

                <?php if (empty($historique)): ?>
                    <p class="text-muted">Aucune operation pour ce client.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Frais</th>
                                <th>Destinataire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique as $h): ?>
                                <tr>
                                    <td><?= esc($h['date_operation']) ?></td>
                                    <td><?= esc(ucfirst($h['type_libelle'])) ?></td>
                                    <td><?= number_format($h['montant'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= number_format($h['frais'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= esc($h['telephone_destinataire'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
