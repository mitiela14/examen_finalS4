<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php $currentPage = 'operation'; ?>
<div class="row">
    <div class="col-md-3 mb-4">
        <?php echo $this->include('client/_sidebar', ['currentPage' => $currentPage]); ?>
    </div>

    <div class="col-md-9">
        <div class="mm-card fade-in-up">
            <div class="card-body p-4">
                <h4 class="fw-700 mb-2" style="color: var(--mm-dark);">
                    <i class="bi bi-plus-circle text-primary"></i> Nouvelle operation
                </h4>
                <p class="text-muted small mb-4">Effectuez un depot, un retrait ou un transfert.</p>

                <div class="d-flex align-items-center gap-3 p-3 mb-4" style="background: var(--mm-primary-light); border-radius: var(--mm-radius-sm); border-left: 4px solid var(--mm-primary);">
                    <i class="bi bi-wallet2 text-primary" style="font-size: 1.5rem;"></i>
                    <div>
                        <small class="text-muted d-block">Solde actuel</small>
                        <strong style="color: var(--mm-primary); font-size: 1.25rem;"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</strong>
                    </div>
                </div>

                <form method="post" action="<?= site_url('client/operation') ?>" id="formOperation">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-tag me-1"></i> Type d'operation</label>
                        <select name="id_type_operation" id="typeOperation" class="form-select form-select-lg" required onchange="toggleOptions()">
                            <option value="">-- Choisir une operation --</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= $type['id_type_operation'] ?>" data-libelle="<?= esc($type['libelle']) ?>">
                                    <?php
                                    $icon = 'bi-plus-circle';
                                    if ($type['libelle'] === 'retrait') $icon = 'bi-cash-stack';
                                    elseif ($type['libelle'] === 'transfert') $icon = 'bi-send';
                                    ?>
                                    <?= esc(ucfirst($type['libelle'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-cash me-1"></i> Montant (Ar)</label>
                        <input type="number" step="0.01" min="1" name="montant" id="montantInput" class="form-control form-control-lg" placeholder="Ex: 50000" required oninput="calculerFrais()">
                    </div>

                    <div class="mb-4 d-none" id="champDestinataire">
                        <label class="form-label"><i class="bi bi-person me-1"></i> Numero du destinataire</label>
                        <input type="text" name="telephone_destinataire" id="destinataireInput" class="form-control" placeholder="0371112222" oninput="calculerFrais()" maxlength="10">
                        <div id="badgeOperateur" class="mt-2"></div>
                    </div>

                    <div class="mb-4 d-none" id="champFraisInclus">
                        <div class="p-3" style="background: var(--mm-light); border-radius: var(--mm-radius-sm); border: 2px solid #e2e8f0;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="frais_inclus" id="fraisInclus" onchange="calculerFrais()" style="cursor: pointer;">
                                <label class="form-check-label fw-600" for="fraisInclus" style="cursor: pointer;">
                                    Inclure les frais dans le montant
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1 ms-4">Si active, les frais sont deduits du montant total debite.</small>
                        </div>
                    </div>

                    <div class="mb-4 d-none" id="champEnvoiMultiple">
                        <div class="p-3" style="background: var(--mm-light); border-radius: var(--mm-radius-sm); border: 2px solid #e2e8f0;">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="envoi_multiple" id="envoiMultiple" onchange="toggleDestinatairesMultiples()" style="cursor: pointer;">
                                <label class="form-check-label fw-600" for="envoiMultiple" style="cursor: pointer;">
                                    Envoi multiple
                                </label>
                            </div>
                            <small class="text-muted d-block ms-4 mb-2">Diviser le montant entre plusieurs destinataires</small>
                            <div class="d-none" id="champDestinatairesMultiples">
                                <textarea name="destinataires_multiples" id="destinatairesMultiples" class="form-control" rows="3" placeholder="0371112222, 0331234567, 0329876543" oninput="calculerFrais()"></textarea>
                                <small class="text-muted d-block mt-1">Separez les numeros par des virgules.</small>
                                <div id="badgesOperateurs" class="mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 d-none" id="resumeFrais" style="animation: fadeInUp 0.3s ease;">
                        <div class="card border-0" style="border-radius: var(--mm-radius); box-shadow: var(--mm-shadow-md); overflow: hidden;">
                            <div class="mm-card-header">
                                <i class="bi bi-calculator me-1"></i> <strong>Resume avant validation</strong>
                            </div>
                            <div class="card-body p-3" id="detailFrais"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3" style="font-size: 1.1rem;">
                        <i class="bi bi-check-circle me-2"></i> Valider l'operation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const FRAIS_TRANSFERT = [
    { min: 0, max: 5000, frais: 100 },
    { min: 5001, max: 20000, frais: 300 },
    { min: 20001, max: 999999999, frais: 700 }
];

const FRAIS_RETRAIT = [
    { min: 0, max: 5000, frais: 200 },
    { min: 5001, max: 20000, frais: 500 },
    { min: 20001, max: 999999999, frais: 1000 }
];

const PREFIXES_OPERATEURS = {
    '033': 'telma', '037': 'telma',
    '032': 'orange', '031': 'airtel'
};

const COMMISSION_AIRTEL = <?= json_encode($commissionAirtel) ?>;

const MON_OPERATEUR = 'airtel';

function getFrais(tranches, montant) {
    for (const t of tranches) {
        if (montant >= t.min && montant <= t.max) return t.frais;
    }
    return 0;
}

function getOperateur(telephone) {
    const prefix = telephone.substring(0, 3);
    return PREFIXES_OPERATEURS[prefix] || null;
}

function badgeOperateur(nomOp) {
    if (!nomOp) return '<span class="badge bg-secondary"><i class="bi bi-question-circle"></i> Inconnu</span>';
    if (nomOp === MON_OPERATEUR) return '<span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Notre operateur (' + nomOp + ')</span>';
    return '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-left-right"></i> Autre operateur (' + nomOp + ')</span>';
}

function toggleOptions() {
    const select = document.getElementById('typeOperation');
    const champDestinataire = document.getElementById('champDestinataire');
    const champFraisInclus = document.getElementById('champFraisInclus');
    const champEnvoiMultiple = document.getElementById('champEnvoiMultiple');
    const resumeFrais = document.getElementById('resumeFrais');
    const libelle = select.options[select.selectedIndex]?.dataset?.libelle;

    champDestinataire.classList.add('d-none');
    champFraisInclus.classList.add('d-none');
    champEnvoiMultiple.classList.add('d-none');
    resumeFrais.classList.add('d-none');

    if (libelle === 'transfert') {
        champDestinataire.classList.remove('d-none');
        champFraisInclus.classList.remove('d-none');
        champEnvoiMultiple.classList.remove('d-none');
    } else if (libelle === 'retrait') {
        champFraisInclus.classList.remove('d-none');
    }

    calculerFrais();
}

function toggleDestinatairesMultiples() {
    const checkbox = document.getElementById('envoiMultiple');
    const champ = document.getElementById('champDestinatairesMultiples');
    const champDestinataire = document.getElementById('champDestinataire');

    if (checkbox.checked) {
        champ.classList.remove('d-none');
        champDestinataire.classList.add('d-none');
        document.getElementById('badgeOperateur').innerHTML = '';
    } else {
        champ.classList.add('d-none');
        champDestinataire.classList.remove('d-none');
    }
    calculerFrais();
}

function calculerFrais() {
    const select = document.getElementById('typeOperation');
    const libelle = select.options[select.selectedIndex]?.dataset?.libelle;
    const montant = parseFloat(document.getElementById('montantInput').value) || 0;
    const fraisInclus = document.getElementById('fraisInclus')?.checked || false;
    const envoiMultiple = document.getElementById('envoiMultiple')?.checked || false;
    const resumeDiv = document.getElementById('resumeFrais');
    const detailDiv = document.getElementById('detailFrais');

    if (montant <= 0 || !libelle || libelle === 'depot') {
        resumeDiv.classList.add('d-none');
        return;
    }

    if (libelle === 'retrait') {
        const frais = getFrais(FRAIS_RETRAIT, montant);
        const totalDebit = fraisInclus ? montant : montant + frais;

        detailDiv.innerHTML = `
            <div class="row text-start g-2">
                <div class="col-6">
                    <div class="text-muted small">Montant demande</div>
                    <div class="fw-700" style="font-size: 1.1rem;">${formatAr(montant)}</div>
                </div>
                <div class="col-6 text-end">
                    <div class="text-muted small">Frais de retrait</div>
                    <div class="fw-700" style="color: var(--mm-danger); font-size: 1.1rem;">${formatAr(frais)}</div>
                </div>
                <div class="col-12"><hr class="my-1"></div>
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total debite</span>
                        <span class="fw-800" style="color: var(--mm-primary); font-size: 1.3rem;">${formatAr(totalDebit)}</span>
                    </div>
                </div>
            </div>
            <div class="mt-2 p-2 rounded small" style="background: ${fraisInclus ? 'var(--mm-success-light)' : 'var(--mm-primary-light)'};">
                <i class="bi bi-info-circle me-1"></i>
                ${fraisInclus ? 'Les frais sont deduits du montant demande.' : 'Les frais sont ajoutes au montant demande.'}
            </div>
        `;
        resumeDiv.classList.remove('d-none');
        return;
    }

    if (libelle === 'transfert') {
        let destinataires = [];
        if (envoiMultiple) {
            const text = document.getElementById('destinatairesMultiples')?.value || '';
            destinataires = text.split(',').map(s => s.trim()).filter(s => s.length >= 3);
            if (destinataires.length === 0) {
                resumeDiv.classList.add('d-none');
                return;
            }
        } else {
            const dest = document.getElementById('destinataireInput')?.value?.trim() || '';
            if (dest.length < 3) {
                resumeDiv.classList.add('d-none');
                return;
            }
            destinataires = [dest];
        }

        const count = destinataires.length;
        const montantParDest = montant / count;

        let rowsHTML = '';
        let totalFrais = 0;
        let totalCommission = 0;
        let totalDebit = 0;
        let totalRecu = 0;
        let nbAutres = 0;
        let nbOwn = 0;

        for (const dest of destinataires) {
            const opDest = getOperateur(dest);
            const estAutre = opDest && opDest !== MON_OPERATEUR;
            const fraisParDest = getFrais(FRAIS_TRANSFERT, montantParDest);
            totalFrais += fraisParDest;

            let commission = 0;
            let montantAEnvoyer = 0;
            let debitThisDest = 0;

            if (estAutre) {
                nbAutres++;
                commission = montantParDest * COMMISSION_AIRTEL / 100;
                totalCommission += commission;
                montantAEnvoyer = montantParDest + commission;

                if (fraisInclus) {
                    debitThisDest = montantParDest + fraisParDest;
                } else {
                    debitThisDest = montantParDest + fraisParDest + commission;
                }
            } else {
                nbOwn++;
                if (fraisInclus) {
                    montantAEnvoyer = montantParDest - fraisParDest;
                    debitThisDest = montantParDest;
                } else {
                    montantAEnvoyer = montantParDest;
                    debitThisDest = montantParDest + fraisParDest;
                }
            }

            totalDebit += debitThisDest;
            totalRecu += montantAEnvoyer;

            rowsHTML += `<tr>
                <td class="fw-500">${escHtml(dest)}</td>
                <td>${badgeOperateur(opDest)}</td>
                <td class="text-end fw-600">${formatAr(montantParDest)}</td>
                <td class="text-end" style="color: var(--mm-danger);">${formatAr(fraisParDest)}</td>
                <td class="text-end" style="color: var(--mm-danger);">${commission > 0 ? formatAr(commission) : '<span class="text-muted">-</span>'}</td>
                <td class="text-end fw-600" style="color: var(--mm-success);">${formatAr(montantAEnvoyer)}</td>
            </tr>`;
        }

        if (count === 1) {
            const dest = destinataires[0];
            const opDest = getOperateur(dest);
            document.getElementById('badgeOperateur').innerHTML = badgeOperateur(opDest);
        } else {
            document.getElementById('badgeOperateur').innerHTML = '';
        }

        detailDiv.innerHTML = `
            <div class="row g-2 mb-3">
                <div class="col-sm-4">
                    <div class="summary-box" style="background: var(--mm-primary-light);">
                        <small style="color: var(--mm-primary);">Total debite</small>
                        <span class="value" style="color: var(--mm-primary);">${formatAr(totalDebit)}</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="summary-box" style="background: var(--mm-success-light);">
                        <small style="color: var(--mm-success);">Total recu</small>
                        <span class="value" style="color: var(--mm-success);">${formatAr(totalRecu)}</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="summary-box" style="background: #f0fdfa;">
                        <small style="color: var(--mm-info);">Destinataires</small>
                        <span class="value" style="color: var(--mm-info);">${count} (${nbOwn} meme, ${nbAutres} autre)</span>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="text-muted small">Montant envoye</div>
                    <div class="fw-700">${formatAr(montant)}</div>
                </div>
                <div class="col-6 text-end">
                    <div class="text-muted small">Frais total</div>
                    <div class="fw-700" style="color: var(--mm-danger);">${formatAr(totalFrais)}</div>
                </div>
                ${totalCommission > 0 ? `
                <div class="col-6">
                    <div class="text-muted small">Commission (${COMMISSION_AIRTEL}%)</div>
                    <div class="fw-700" style="color: var(--mm-danger);">${formatAr(totalCommission)}</div>
                </div>
                ` : ''}
            </div>

            ${count > 1 ? `
            <div class="table-responsive">
                <table class="table table-sm mb-0" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th>Destinataire</th>
                            <th>Operateur</th>
                            <th class="text-end">Montant</th>
                            <th class="text-end">Frais</th>
                            <th class="text-end">Commission</th>
                            <th class="text-end">Recu</th>
                        </tr>
                    </thead>
                    <tbody>${rowsHTML}</tbody>
                </table>
            </div>
            ` : ''}

            <div class="mt-3 p-2 rounded small" style="background: ${fraisInclus ? 'var(--mm-success-light)' : 'var(--mm-primary-light)'};">
                <i class="bi bi-info-circle me-1"></i>
                ${fraisInclus ? 'Les frais sont inclus dans le montant debite.' : 'Frais et commission ajoutes au montant demande.'}
            </div>
        `;
        resumeDiv.classList.remove('d-none');
    }
}

function formatAr(montant) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(montant)) + ' Ar';
}

function escHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>

<?= $this->endSection() ?>
