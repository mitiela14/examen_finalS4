<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                <h5 class="mb-3">Ajouter une commission inter-operateur</h5>
                        <form method="post" action="<?= site_url('admin/commissions/add') ?>" class="row g-2">
                            <?= csrf_field() ?>
                            <div class="col-md-5">
                                <select name="operateur" class="form-select" required>
                                    <option value="telma">Telma (nous)</option>
                                    <option value="orange">Orange</option>
                                    <option value="airtel">Airtel</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="0.01" min="0" max="100" name="pourcentage_autres" class="form-control" placeholder="ex: 5.00" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                <div class="card-body">
                <h5 class="mb-3">Commissions configurees</h5>

                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Operateur</th>
                            <th>Pourcentage (%)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commissions as $c): ?>
                            <tr>
                                <td><?= esc($c['operateur']) ?></td>
                                    <td>
                                        <form method="post" action="<?= site_url('admin/commissions/update/' . $c['id_commission']) ?>" class="d-flex gap-2">
                                            <?= csrf_field() ?>
                                            <input type="number" step="0.01" min="0" max="100" name="pourcentage_autres" value="<?= esc($c['pourcentage_autres']) ?>" class="form-control form-control-sm" style="width:100px">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Enregistrer</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="post" action="<?= site_url('admin/commissions/delete/' . $c['id_commission']) ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
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