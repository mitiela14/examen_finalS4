<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4"><?= $this->include('admin/_sidebar') ?></div>

    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="mb-3">Gains via les frais</h4>

                <div class="p-4 bg-success text-white rounded text-center mb-4">
                    <div class="small">Total des gains</div>
                    <div class="display-6"><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</div>
                </div>

                <?php if (empty($gains)): ?>
                    <p class="text-muted">Aucune operation enregistree pour le moment.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type d'operation</th>
                                <th>Nombre d'operations</th>
                                <th>Total des frais</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gains as $g): ?>
                                <tr>
                                    <td><?= esc(ucfirst($g['type_operation'])) ?></td>
                                    <td><?= esc($g['nombre']) ?></td>
                                    <td><?= number_format($g['total_frais'], 0, ',', ' ') ?> Ar</td>
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
