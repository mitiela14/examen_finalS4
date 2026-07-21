<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                <h5 class="mb-3">Ajouter une promotion</h5>
                        <form method="post" action="<?= site_url('admin/promotion/add') ?>" class="row g-2">
                            <?= csrf_field() ?>
                            <div class="col-md-5">
                                <input type="number" step="0.01" min="0" max="100" name="pourcentage" class="form-control" placeholder="ex: 10.00" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                <div class="card-body">
                <h5 class="mb-3">Promotions actives</h5>

                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Pourcentage(%)</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promotion as $p): ?>
                            <tr>
                                <td><?= esc($p['pourcentage']) ?></td>
                                <td><?= esc($p['date_expiration']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>