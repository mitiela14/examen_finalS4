<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Situation des comptes clients</h4>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Telephone</th>
                            <th>Solde</th>
                            <th>Date creation</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td><?= esc($c['telephone']) ?></td>
                                <td><?= number_format($c['solde'], 0, ',', ' ') ?> Ar</td>
                                <td><?= esc($c['date_creation']) ?></td>
                                <td>
                                    <a href="<?= site_url('admin/clients/' . $c['id_utilisateur']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
