<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="<?= site_url('client/dashboard') ?>" class="list-group-item list-group-item-action">Voir solde</a>
            <a href="<?= site_url('client/operation') ?>" class="list-group-item list-group-item-action">Nouvelle operation</a>
            <a href="<?= site_url('client/historique') ?>" class="list-group-item list-group-item-action active">Historique</a>
            <a href="<?= site_url('client/profil') ?>" class="list-group-item list-group-item-action">Mon profil</a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Historique des operations</h4>

                <?php if (empty($historique)): ?>
                    <p class="text-muted">Aucune operation pour le moment.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Frais</th>
                                <th>Destinataire</th>
                                <th>Solde apres</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique as $h): ?>
                                <tr>
                                    <td><?= esc($h['date_operation']) ?></td>
                                    <td><span class="badge bg-secondary"><?= esc(ucfirst($h['type_libelle'])) ?></span></td>
                                    <td><?= number_format($h['montant'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= number_format($h['frais'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= esc($h['telephone_destinataire'] ?? '-') ?></td>
                                    <td><?= number_format($h['solde_apres'], 0, ',', ' ') ?> Ar</td>
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
