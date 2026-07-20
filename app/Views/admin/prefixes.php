<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Ajouter un prefixe</h5>
                <form method="post" action="<?= site_url('admin/prefixes/add') ?>" class="row g-2">
                    <?= csrf_field() ?>
                    <div class="col-md-5">
                        <input type="text" name="code" maxlength="3" class="form-control" placeholder="ex: 033" required>
                    </div>
                    <div class="col-md-4">
                        <select name="operateur" class="form-select" required>
                            <option value="telma">Telma (nous)</option>
                            <option value="orange">Orange</option>
                            <option value="airtel">Airtel</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>  
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Prefixes autorises</h5>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Operateur</th>
                            <th>Date d'ajout</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prefixes as $p): ?>
                            <tr>
                                <td><?= esc($p['code']) ?></td>
                                <td>
                                    <form method="post" action="<?= site_url('admin/prefixes/update/' . $p['id_prefixe']) ?>" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                        <select name="operateur" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="telma" <?= $p['operateur'] === 'telma' ? 'selected' : '' ?>>Telma</option>
                                            <option value="orange" <?= $p['operateur'] === 'orange' ? 'selected' : '' ?>>Orange</option>
                                            <option value="airtel" <?= $p['operateur'] === 'airtel' ? 'selected' : '' ?>>Airtel</option>
                                        </select>
                                    </form>
                                </td>
                                                                <td><?= esc($p['date_ajout']) ?></td>
                                <td>
                                    <form method="post" action="<?= site_url('admin/prefixes/delete/' . $p['id_prefixe']) ?>">
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
