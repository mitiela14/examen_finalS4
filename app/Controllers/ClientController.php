<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheMontantModel;
use App\Models\HistoriqueModel;

class ClientController extends BaseController
{
    protected UtilisateurModel $utilisateurModel;
    protected PrefixeModel $prefixeModel;
    protected TypeOperationModel $typeOperationModel;
    protected TrancheMontantModel $trancheModel;
    protected HistoriqueModel $historiqueModel;

    public function __construct()
    {
        $this->utilisateurModel  = new UtilisateurModel();
        $this->prefixeModel      = new PrefixeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->trancheModel      = new TrancheMontantModel();
        $this->historiqueModel   = new HistoriqueModel();
    }

    // LOGIN (automatique par numero de telephone)
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

        // 1. Verifier le prefixe
        if (! $this->prefixeModel->isValid($telephone)) {
            return redirect()->back()->with('error', 'Ce prefixe n\'est pas pris en charge par l\'operateur.');
        }

        // 2. Chercher le client, sinon le creer automatiquement
        $client = $this->utilisateurModel->findByTelephone($telephone);

        if (! $client) {
            $id = $this->utilisateurModel->insert([
                'telephone' => $telephone,
                'solde'     => 0,
            ], true);
            $client = $this->utilisateurModel->find($id);
        }

        // Un compte suspendu par l'operateur ne doit pas pouvoir se connecter
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

    // DASHBOARD
    public function dashboard(): string
    {
        $this->ensureLoggedIn();

        $client = $this->utilisateurModel->find(session()->get('client_id'));

        return view('client/dashboard', ['client' => $client]);
    }

    // FORMULAIRE D'OPERATION (depot / retrait / transfert)
    public function operation(): string
    {
        $this->ensureLoggedIn();

        $types  = $this->typeOperationModel->findAll();
        $client = $this->utilisateurModel->find(session()->get('client_id'));

        return view('client/operation', ['types' => $types, 'client' => $client]);
    }

    public function doOperation()
    {
        $this->ensureLoggedIn();

        $idType   = (int) $this->request->getPost('id_type_operation');
        $montant  = (float) $this->request->getPost('montant');
        $destinataire = trim((string) $this->request->getPost('telephone_destinataire'));

        $type   = $this->typeOperationModel->find($idType);
        $client = $this->utilisateurModel->find(session()->get('client_id'));

        if (! $type || $montant <= 0) {
            return redirect()->back()->with('error', 'Operation invalide.');
        }

        $frais = $this->trancheModel->getFrais($idType, $montant);

        // Logique selon le type d'operation (libelle : depot / retrait / transfert)
        switch ($type['libelle']) {
            case 'depot':
                $nouveauSolde = $client['solde'] + $montant; // pas de frais sur le depot
                $frais = 0;
                break;

            case 'retrait':
                if ($montant + $frais > $client['solde']) {
                    return redirect()->back()->with('error', 'Solde insuffisant pour ce retrait.');
                }
                $nouveauSolde = $client['solde'] - $montant - $frais;
                break;

            case 'transfert':
                if (empty($destinataire)) {
                    return redirect()->back()->with('error', 'Veuillez indiquer le numero du destinataire.');
                }
                if ($montant + $frais > $client['solde']) {
                    return redirect()->back()->with('error', 'Solde insuffisant pour ce transfert.');
                }

                $compteDestinataire = $this->utilisateurModel->findByTelephone($destinataire);
                if (! $compteDestinataire) {
                    return redirect()->back()->with('error', 'Le numero destinataire est introuvable.');
                }

                // Crediter le destinataire
                $this->utilisateurModel->update($compteDestinataire['id_utilisateur'], [
                    'solde' => $compteDestinataire['solde'] + $montant,
                ]);

                $nouveauSolde = $client['solde'] - $montant - $frais;
                break;

            default:
                return redirect()->back()->with('error', 'Type d\'operation inconnu.');
        }

        // Mettre a jour le solde du client courant
        $this->utilisateurModel->update($client['id_utilisateur'], ['solde' => $nouveauSolde]);

        // Enregistrer dans l'historique
        $this->historiqueModel->insert([
            'id_utilisateur'          => $client['id_utilisateur'],
            'id_type_operation'       => $idType,
            'montant'                 => $montant,
            'frais'                   => $frais,
            'telephone_destinataire'  => $type['libelle'] === 'transfert' ? $destinataire : null,
            'solde_apres'             => $nouveauSolde,
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Operation effectuee avec succes.');
    }

    // MON PROFIL (modification du nom uniquement - le statut reste gere par l'operateur)
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

        // On ne met a jour que le nom : le client ne doit pas pouvoir changer son propre statut
        $this->utilisateurModel->update(session()->get('client_id'), ['nom' => $nom]);

        return redirect()->to('/client/profil')->with('success', 'Profil mis a jour.');
    }

    // HISTORIQUE
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
