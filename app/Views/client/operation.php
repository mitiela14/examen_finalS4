<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="<?= site_url('client/dashboard') ?>" class="list-group-item list-group-item-action">Voir solde</a>
            <a href="<?= site_url('client/operation') ?>" class="list-group-item list-group-item-action active">Nouvelle operation</a>
            <a href="<?= site_url('client/historique') ?>" class="list-group-item list-group-item-action">Historique</a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Nouvelle operation</h4>
                <p>Solde actuel : <strong><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong></p>

                <form method="post" action="<?= site_url('client/operation') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Type d'operation</label>
                        <select name="id_type_operation" id="typeOperation" class="form-select" required onchange="toggleDestinataire()">
                            <option value="">-- Choisir --</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= $type['id_type_operation'] ?>" data-libelle="<?= esc($type['libelle']) ?>">
                                    <?= esc(ucfirst($type['libelle'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Montant (Ar)</label>
                        <input type="number" step="0.01" min="1" name="montant" class="form-control" required>
                    </div>

                    <div class="mb-3 d-none" id="champDestinataire">
                        <label class="form-label">Numero du destinataire</label>
                        <input type="text" name="telephone_destinataire" class="form-control" placeholder="0371112222">
                    </div>

                    <button type="submit" class="btn btn-primary">Valider l'operation</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Affiche le champ "destinataire" uniquement si le type choisi est "transfert"
function toggleDestinataire() {
    const select = document.getElementById('typeOperation');
    const champ = document.getElementById('champDestinataire');
    const libelle = select.options[select.selectedIndex]?.dataset?.libelle;

    if (libelle === 'transfert') {
        champ.classList.remove('d-none');
    } else {
        champ.classList.add('d-none');
    }
}
</script>

<?= $this->endSection() ?>
