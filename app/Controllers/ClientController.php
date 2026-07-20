<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheMontantModel;
use App\Models\HistoriqueModel;
use App\Models\CommissionInterOperateurModel;
use App\Models\SoldeOperateurModel;

class ClientController extends BaseController
{
    protected UtilisateurModel $utilisateurModel;
    protected PrefixeModel $prefixeModel;
    protected TypeOperationModel $typeOperationModel;
    protected TrancheMontantModel $trancheModel;
    protected HistoriqueModel $historiqueModel;
    protected CommissionInterOperateurModel $commissionModel;
    protected SoldeOperateurModel $soldeOperateurModel;

    public function __construct()
    {
        $this->utilisateurModel      = new UtilisateurModel();
        $this->prefixeModel          = new PrefixeModel();
        $this->typeOperationModel    = new TypeOperationModel();
        $this->trancheModel          = new TrancheMontantModel();
        $this->historiqueModel       = new HistoriqueModel();
        $this->commissionModel       = new CommissionInterOperateurModel();
        $this->soldeOperateurModel   = new SoldeOperateurModel();
    }

    public function login(): string
    {
        return view('client/login');
    }

    public function doLogin()
    {
        $telephone = trim($this->request->getPost('telephone'));

        if (empty($telephone)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numero de telephone.');
        }

        if (! $this->prefixeModel->isValid($telephone)) {
            return redirect()->back()->with('error', 'Ce prefixe n\'est pas pris en charge par l\'operateur.');
        }

        $monOperateur = $this->prefixeModel->getOperateurByTelephone($telephone);
        if ($monOperateur !== 'airtel') {
            return redirect()->back()->with('error', 'Seuls les clients Airtel (031) peuvent se connecter ici.');
        }

        $client = $this->utilisateurModel->findByTelephone($telephone);

        if (! $client) {
            $id = $this->utilisateurModel->insert([
                'telephone' => $telephone,
                'solde'     => 0,
            ], true);
            $client = $this->utilisateurModel->find($id);
        }

        if ($client['statut'] === 'suspendu') {
            return redirect()->back()->with('error', 'Ce compte est suspendu. Contactez l\'operateur.');
        }

        session()->set([
            'client_id'        => $client['id_utilisateur'],
            'client_telephone' => $client['telephone'],
            'isClientLoggedIn' => true,
        ]);

        return redirect()->to('/client/dashboard');
    }

    public function dashboard(): string
    {
        $this->ensureLoggedIn();
        $client = $this->utilisateurModel->find(session()->get('client_id'));
        return view('client/dashboard', ['client' => $client]);
    }

    public function operation(): string
    {
        $this->ensureLoggedIn();
        $types  = $this->typeOperationModel->findAll();
        $client = $this->utilisateurModel->find(session()->get('client_id'));
        $commissionAirtel = $this->commissionModel->getCommission('airtel');
        return view('client/operation', ['types' => $types, 'client' => $client, 'commissionAirtel' => $commissionAirtel]);
    }

    public function doOperation()
    {
        $this->ensureLoggedIn();

        $idType   = (int) $this->request->getPost('id_type_operation');
        $montant  = (float) $this->request->getPost('montant');
        $destinataire = trim((string) $this->request->getPost('telephone_destinataire'));
        $fraisInclus = (bool) $this->request->getPost('frais_inclus');
        $estEnvoiMultiple = (bool) $this->request->getPost('envoi_multiple');
        $destinatairesMultiples = $this->request->getPost('destinataires_multiples');

        $type   = $this->typeOperationModel->find($idType);
        $client = $this->utilisateurModel->find(session()->get('client_id'));

        if (! $type || $montant <= 0) {
            return redirect()->back()->with('error', 'Operation invalide.');
        }

        $frais = $this->trancheModel->getFrais($idType, $montant);

        switch ($type['libelle']) {
            case 'depot':
                $nouveauSolde = $client['solde'] + $montant;
                $this->utilisateurModel->update($client['id_utilisateur'], ['solde' => $nouveauSolde]);
                $this->historiqueModel->insert([
                    'id_utilisateur'          => $client['id_utilisateur'],
                    'id_type_operation'       => $idType,
                    'montant'                 => $montant,
                    'frais'                   => 0,
                    'telephone_destinataire'  => null,
                    'solde_apres'             => $nouveauSolde,
                    'frais_inclus'            => 0,
                    'est_envoi_multiple'      => 0,
                    'commission'              => 0,
                    'montant_recu'            => $montant,
                    'montant_debit'           => $montant,
                ]);
                return redirect()->to('/client/dashboard')->with('success', 'Depot de ' . number_format($montant, 0, ',', ' ') . ' Ar effectue avec succes.');

            case 'retrait':
                if ($fraisInclus) {
                    $montantTotal = $montant;
                    $nouveauSolde = $client['solde'] - $montantTotal;
                } else {
                    $montantTotal = $montant + $frais;
                    $nouveauSolde = $client['solde'] - $montantTotal;
                }
                if ($montantTotal > $client['solde']) {
                    return redirect()->back()->with('error', 'Solde insuffisant pour ce retrait. Total requis : ' . number_format($montantTotal, 0, ',', ' ') . ' Ar.');
                }
                $this->utilisateurModel->update($client['id_utilisateur'], ['solde' => $nouveauSolde]);
                $this->historiqueModel->insert([
                    'id_utilisateur'          => $client['id_utilisateur'],
                    'id_type_operation'       => $idType,
                    'montant'                 => $montant,
                    'frais'                   => $frais,
                    'telephone_destinataire'  => null,
                    'solde_apres'             => $nouveauSolde,
                    'frais_inclus'            => $fraisInclus ? 1 : 0,
                    'est_envoi_multiple'      => 0,
                    'commission'              => 0,
                    'montant_recu'            => $montant,
                    'montant_debit'           => $montantTotal,
                ]);
                return redirect()->to('/client/dashboard')->with('success', 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' Ar effectue avec succes.');

            case 'transfert':
                if ($estEnvoiMultiple) {
                    $result = $this->traiterEnvoiMultiple(
                        $destinatairesMultiples, $montant, $frais,
                        $fraisInclus, $idType, $client
                    );
                } else {
                    $result = $this->traiterTransfertSimple(
                        $destinataire, $montant, $frais,
                        $fraisInclus, $idType, $client
                    );
                }
                if (is_string($result)) {
                    return redirect()->back()->with('error', $result);
                }
                return redirect()->to('/client/dashboard')->with('success', 'Transfert effectue avec succes.');

            default:
                return redirect()->back()->with('error', 'Type d\'operation inconnu.');
        }
    }

    private function traiterTransfertSimple(
        string $destinataire,
        float $montant,
        float $frais,
        bool $fraisInclus,
        int $idType,
        array $client
    ): string|float {
        if (empty($destinataire)) {
            return 'Veuillez indiquer le numero du destinataire.';
        }

        $monOperateur = $this->prefixeModel->getOperateurByTelephone($client['telephone']) ?? 'airtel';
        $opDestinataire = $this->prefixeModel->getOperateurByTelephone($destinataire);

        if (! $opDestinataire) {
            return 'Le prefixe du destinataire n\'est pas pris en charge.';
        }

        $estAutreOperateur = ($opDestinataire !== $monOperateur);

        $compteDestinataire = null;
        if (! $estAutreOperateur) {
            $compteDestinataire = $this->utilisateurModel->findByTelephone($destinataire);
            if (! $compteDestinataire) {
                return 'Ce numero n\'appartient pas a notre operateur.';
            }
        }

        $commission = 0;
        $montantAEnvoyer = 0;
        $montantTotal = 0;

        if ($estAutreOperateur) {
            $taux = $this->commissionModel->getCommission($monOperateur);
            $commission = $montant * $taux / 100;
            $montantAEnvoyer = $montant + $commission;

            if ($fraisInclus) {
                $montantTotal = $montant + $frais;
            } else {
                $montantTotal = $montant + $frais + $commission;
            }

            $this->soldeOperateurModel->ajouterMontant($opDestinataire, $montantAEnvoyer);
        } else {
            if ($fraisInclus) {
                $montantTotal = $montant;
                $montantAEnvoyer = $montant - $frais;
                if ($montantAEnvoyer < 0) {
                    return 'Le montant est trop faible pour couvrir les frais de transfert (frais : ' . number_format($frais, 0, ',', ' ') . ' Ar).';
                }
            } else {
                $montantTotal = $montant + $frais;
                $montantAEnvoyer = $montant;
            }
        }

        if ($montantTotal > $client['solde']) {
            return 'Solde insuffisant pour ce transfert. Total a debiter : '
                . number_format($montantTotal, 0, ',', ' ') . ' Ar. '
                . 'Solde actuel : ' . number_format($client['solde'], 0, ',', ' ') . ' Ar.';
        }

        if (! $estAutreOperateur && $compteDestinataire) {
            $this->utilisateurModel->update($compteDestinataire['id_utilisateur'], [
                'solde' => $compteDestinataire['solde'] + $montantAEnvoyer,
            ]);
        }

        $nouveauSolde = $client['solde'] - $montantTotal;
        $this->utilisateurModel->update($client['id_utilisateur'], ['solde' => $nouveauSolde]);

        $this->historiqueModel->insert([
            'id_utilisateur'          => $client['id_utilisateur'],
            'id_type_operation'       => $idType,
            'montant'                 => $montant,
            'frais'                   => $frais,
            'telephone_destinataire'  => $destinataire,
            'solde_apres'             => $nouveauSolde,
            'frais_inclus'            => $fraisInclus ? 1 : 0,
            'est_envoi_multiple'      => 0,
            'commission'              => $commission,
            'operateur_destinataire'  => $opDestinataire,
            'montant_recu'            => $montantAEnvoyer,
            'montant_debit'           => $montantTotal,
        ]);

        return $nouveauSolde;
    }

    private function traiterEnvoiMultiple(
        ?string $destinatairesMultiples,
        float $montant,
        float $fraisTotal,
        bool $fraisInclus,
        int $idType,
        array $client
    ): string|float {
        $destinatairesArray = array_filter(array_map('trim', explode(',', $destinatairesMultiples ?? '')));
        if (empty($destinatairesArray)) {
            return 'Veuillez indiquer au moins un destinataire.';
        }

        $count = count($destinatairesArray);
        $montantParDest = $montant / $count;

        $monOperateur = $this->prefixeModel->getOperateurByTelephone($client['telephone']) ?? 'airtel';

        foreach ($destinatairesArray as $dest) {
            $opDest = $this->prefixeModel->getOperateurByTelephone($dest);
            if (! $opDest) {
                return 'Le prefixe du destinataire ' . esc($dest) . ' n\'est pas pris en charge.';
            }
            $estAutre = ($opDest !== $monOperateur);
            if (! $estAutre) {
                $compteDest = $this->utilisateurModel->findByTelephone($dest);
                if (! $compteDest) {
                    return 'Le numero ' . esc($dest) . ' n\'appartient pas a notre operateur.';
                }
            }
        }

        // Calculer le total debite AVANT d'effectuer les transferts
        $totalDebit = 0;
        $totalCommission = 0;

        foreach ($destinatairesArray as $dest) {
            $fraisParDest = $this->trancheModel->getFrais($idType, $montantParDest);
            $opDest = $this->prefixeModel->getOperateurByTelephone($dest);
            $estAutre = ($opDest !== $monOperateur);

            if ($estAutre) {
                $taux = $this->commissionModel->getCommission($monOperateur);
                $commission = $montantParDest * $taux / 100;
                $totalCommission += $commission;

                if ($fraisInclus) {
                    $totalDebit += $montantParDest + $fraisParDest;
                } else {
                    $totalDebit += $montantParDest + $fraisParDest + $commission;
                }
            } else {
                if ($fraisInclus) {
                    $totalDebit += $montantParDest;
                } else {
                    $totalDebit += $montantParDest + $fraisParDest;
                }
            }
        }

        // VERIFICATION DE SOLVABILITE AVANT tout transfert
        if ($totalDebit > $client['solde']) {
            return 'Solde insuffisant pour ce transfert multiple. Total a debiter : '
                . number_format($totalDebit, 0, ',', ' ') . ' Ar. '
                . 'Solde actuel : ' . number_format($client['solde'], 0, ',', ' ') . ' Ar.';
        }

        // Maintenant effectuer les transferts
        $totalMontantRecu = 0;
        $soldeTemporaire = $client['solde'];

        foreach ($destinatairesArray as $dest) {
            $fraisParDest = $this->trancheModel->getFrais($idType, $montantParDest);
            $opDest = $this->prefixeModel->getOperateurByTelephone($dest);
            $estAutre = ($opDest !== $monOperateur);

            $commission = 0;
            $montantAEnvoyer = 0;
            $debitThisDest = 0;

            if ($estAutre) {
                $taux = $this->commissionModel->getCommission($monOperateur);
                $commission = $montantParDest * $taux / 100;
                $montantAEnvoyer = $montantParDest + $commission;

                if ($fraisInclus) {
                    $debitThisDest = $montantParDest + $fraisParDest;
                } else {
                    $debitThisDest = $montantParDest + $fraisParDest + $commission;
                }

                $this->soldeOperateurModel->ajouterMontant($opDest, $montantAEnvoyer);
            } else {
                if ($fraisInclus) {
                    $montantAEnvoyer = $montantParDest - $fraisParDest;
                    $debitThisDest = $montantParDest;
                } else {
                    $montantAEnvoyer = $montantParDest;
                    $debitThisDest = $montantParDest + $fraisParDest;
                }

                $compteDest = $this->utilisateurModel->findByTelephone($dest);
                $this->utilisateurModel->update($compteDest['id_utilisateur'], [
                    'solde' => $compteDest['solde'] + $montantAEnvoyer,
                ]);
            }

            $totalMontantRecu += $montantAEnvoyer;
            $soldeTemporaire -= $debitThisDest;

            $this->historiqueModel->insert([
                'id_utilisateur'          => $client['id_utilisateur'],
                'id_type_operation'       => $idType,
                'montant'                 => $montantParDest,
                'frais'                   => $fraisParDest,
                'telephone_destinataire'  => $dest,
                'solde_apres'             => $soldeTemporaire,
                'frais_inclus'            => $fraisInclus ? 1 : 0,
                'est_envoi_multiple'      => 1,
                'reference_envoi'         => 'ENV' . date('YmdHis') . rand(1000, 9999),
                'commission'              => $commission,
                'operateur_destinataire'  => $opDest,
                'montant_recu'            => $montantAEnvoyer,
                'montant_debit'           => $debitThisDest,
            ]);
        }

        $nouveauSolde = $client['solde'] - $totalDebit;
        $this->utilisateurModel->update($client['id_utilisateur'], ['solde' => $nouveauSolde]);

        return $nouveauSolde;
    }

    public function profil(): string
    {
        $this->ensureLoggedIn();
        $client = $this->utilisateurModel->find(session()->get('client_id'));
        return view('client/profil', ['client' => $client]);
    }

    public function doProfil()
    {
        $this->ensureLoggedIn();
        $nom = trim((string) $this->request->getPost('nom'));

        if (empty($nom)) {
            return redirect()->back()->with('error', 'Veuillez saisir un nom.');
        }

        $this->utilisateurModel->update(session()->get('client_id'), ['nom' => $nom]);
        return redirect()->to('/client/profil')->with('success', 'Profil mis a jour.');
    }

    public function historique(): string
    {
        $this->ensureLoggedIn();
        $historique = $this->historiqueModel->historiqueClient(session()->get('client_id'));
        return view('client/historique', ['historique' => $historique]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    private function ensureLoggedIn(): void
    {
        if (! session()->get('isClientLoggedIn')) {
            redirect()->to('/client/login')->send();
            exit;
        }
    }
}
