<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

    <div class="col-md-9">

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Ajouter une tranche</h5>
                <form method="post" action="<?= site_url('admin/tranches/add') ?>" class="row g-2">
                    <?= csrf_field() ?>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="id_type_operation" class="form-select" required>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= $type['id_type_operation'] ?>"><?= esc(ucfirst($type['libelle'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Montant min</label>
                        <input type="number" step="0.01" name="montant_min" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Montant max</label>
                        <input type="number" step="0.01" name="montant_max" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Frais</label>
                        <input type="number" step="0.01" name="frais" class="form-control" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">+</button>
                    </div>
                </form>
            </div>
        </div>

        <?php foreach ($types as $type): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Bareme - <?= esc(ucfirst($type['libelle'])) ?></h5>

                    <?php $tranches = $tranchesParType[$type['id_type_operation']] ?? []; ?>

                    <?php if (empty($tranches)): ?>
                        <p class="text-muted">Aucune tranche definie.</p>
                    <?php else: ?>
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr><th>Min</th><th>Max</th><th>Frais</th><th></th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tranches as $t): ?>
                                    <tr>
                                        <td><?= number_format($t['montant_min'], 0, ',', ' ') ?></td>
                                        <td><?= number_format($t['montant_max'], 0, ',', ' ') ?></td>
                                        <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                                        <td>
                                            <form method="post" action="<?= site_url('admin/tranches/delete/' . $t['id_tranche']) ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<?= $this->endSection() ?>
