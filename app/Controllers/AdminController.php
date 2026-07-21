<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheMontantModel;
use App\Models\HistoriqueModel;
use App\Models\CommissionInterOperateurModel;
use App\Models\UtilisateurModel;
use App\Models\SoldeOperateurModel;
use App\Models\PromotionModel;


class AdminController extends BaseController
{
    protected AdminModel $adminModel;
    protected PrefixeModel $prefixeModel;
    protected TypeOperationModel $typeOperationModel;
    protected TrancheMontantModel $trancheModel;
    protected HistoriqueModel $historiqueModel;
    protected CommissionInterOperateurModel $commissionModel;
    protected UtilisateurModel $utilisateurModel;
    protected SoldeOperateurModel $soldeOperateurModel;
  
    protected PromotionModel $promotionModel;
    public function __construct()
    {
        $this->adminModel        = new AdminModel();
        $this->prefixeModel      = new PrefixeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->trancheModel      = new TrancheMontantModel();
        $this->historiqueModel   = new HistoriqueModel();
        $this->commissionModel = new CommissionInterOperateurModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->soldeOperateurModel = new SoldeOperateurModel();
    }

    // ---------------------------------------------------------------
    // LOGIN (par code d'acces)
    // ---------------------------------------------------------------
    public function login(): string
    {
        return view('admin/login');
    }

    public function doLogin()
    {
        $code = trim($this->request->getPost('code_acces'));

        $admin = $this->adminModel->findByCode($code);

        if (! $admin) {
            return redirect()->back()->with('error', 'Code d\'acces incorrect.');
        }

        session()->set([
            'admin_id'        => $admin['id_admin'],
            'admin_nom'       => $admin['nom'],
            'isAdminLoggedIn' => true,
        ]);

        return redirect()->to('/admin/dashboard');
    }

    // DASHBOARD : gains par type d'operation
    // ---------------------------------------------------------------
    public function dashboard(): string
    {
        $this->ensureLoggedIn();

        $gains = $this->historiqueModel->totalGainsParType();
        $gainsParOperateur = $this->historiqueModel->totalGainsParOperateur();
        $totalGeneral = array_sum(array_column($gains, 'total_frais')) + array_sum(array_column($gains, 'total_commission'));
        $totalFrais = array_sum(array_column($gains, 'total_frais'));
        $totalCommission = array_sum(array_column($gains, 'total_commission'));
        $soldesOperateurs = $this->soldeOperateurModel->findAll();

        return view('admin/dashboard', [
            'gains' => $gains,
            'gainsParOperateur' => $gainsParOperateur,
            'totalGeneral' => $totalGeneral,
            'totalFrais' => $totalFrais,
            'totalCommission' => $totalCommission,
            'soldesOperateurs' => $soldesOperateurs,
        ]);
    }

      // LISTE DES CLIENTS + DETAIL
    // ---------------------------------------------------------------
    public function clients(): string
    {
        $this->ensureLoggedIn();

        $clients = $this->utilisateurModel->findAll();

        return view('admin/clients', ['clients' => $clients]);
    }

    public function clientDetail(int $id): string
    {
        $this->ensureLoggedIn();

        $client     = $this->utilisateurModel->find($id);
        $historique = $this->historiqueModel->historiqueClient($id);

        return view('admin/client_detail', ['client' => $client, 'historique' => $historique]);
    }

     // GESTION DES TRANCHES (CRUD simplifie)
    // ---------------------------------------------------------------
    public function tranches(): string
    {
        $this->ensureLoggedIn();

        $types = $this->typeOperationModel->findAll();
        $tranchesParType = [];
        foreach ($types as $type) {
            $tranchesParType[$type['id_type_operation']] = $this->trancheModel->listByType($type['id_type_operation']);
        }

        return view('admin/tranches', ['types' => $types, 'tranchesParType' => $tranchesParType]);
    }

    public function addTranche()
    {
        $this->ensureLoggedIn();

        $this->trancheModel->insert([
            'id_type_operation' => (int) $this->request->getPost('id_type_operation'),
            'montant_min'       => (float) $this->request->getPost('montant_min'),
            'montant_max'       => (float) $this->request->getPost('montant_max'),
            'frais'             => (float) $this->request->getPost('frais'),
        ]);

        return redirect()->to('/admin/tranches')->with('success', 'Tranche ajoutee.');
    }

    public function deleteTranche(int $id)
    {
        $this->ensureLoggedIn();
        $this->trancheModel->delete($id);
        return redirect()->to('/admin/tranches')->with('success', 'Tranche supprimee.');
    }

      // CONFIGURATION DES PREFIXES (CRUD simplifie)
    // ---------------------------------------------------------------
    public function prefixes(): string
    {
        $this->ensureLoggedIn();

        $prefixes = $this->prefixeModel->findAll();

        return view('admin/prefixes', ['prefixes' => $prefixes]);
    }

    public function addPrefixe()
    {
        $this->ensureLoggedIn();

        $code = trim($this->request->getPost('code'));
        $operateur = trim($this->request->getPost('operateur')) ?: 'telma';
        if (! empty($code)) {
            $this->prefixeModel->insert(['code' => $code, 'operateur' => $operateur]);
        }

        return redirect()->to('/admin/prefixes')->with('success', 'Prefixe ajoute.');
    }

    public function updatePrefixe(int $id)
    {
        $this->ensureLoggedIn();

        $this->prefixeModel->update($id, [
            'operateur' => trim($this->request->getPost('operateur')),
        ]);

        return redirect()->to('/admin/prefixes')->with('success', 'Prefixe mis a jour.');
    }

    public function deletePrefixe(int $id)
    {
        $this->ensureLoggedIn();
        $this->prefixeModel->delete($id);
        return redirect()->to('/admin/prefixes')->with('success', 'Prefixe supprime.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    private function ensureLoggedIn(): void
    {
        if (! session()->get('isAdminLoggedIn')) {
            redirect()->to('/admin/login')->send();
            exit;
        }
    }

        // ---------------------------------------------------------------
    // COMMISSIONS INTER-OPERATEUR
    // ---------------------------------------------------------------
    public function commissions(): string
    {
        $this->ensureLoggedIn();

        $commissions = $this->commissionModel->findAll();

        return view('admin/commissions', ['commissions' => $commissions]);
    }

    public function addCommission()
    {
        $this->ensureLoggedIn();

        $operateur   = trim($this->request->getPost('operateur'));
        $pourcentage = (float) $this->request->getPost('pourcentage_autres');

        if (! empty($operateur)) {
            $this->commissionModel->updatePourcentage($operateur, $pourcentage);
        }

        return redirect()->to('/admin/commissions')->with('success', 'Commission enregistree.');
    }

    public function updateCommission(int $id)
    {
        $this->ensureLoggedIn();

        $this->commissionModel->update($id, [
            'pourcentage_autres' => (float) $this->request->getPost('pourcentage_autres'),
        ]);

        return redirect()->to('/admin/commissions')->with('success', 'Commission mise a jour.');
    }

    public function deleteCommission(int $id)
    {
        $this->ensureLoggedIn();
        $this->commissionModel->delete($id);
        return redirect()->to('/admin/commissions')->with('success', 'Commission supprimee.');
    }


    public function promotions(): string{
        
        $this->ensureLoggedIn();
        $promotion = $this->promotionModel->getPromotion();

        return view('admin/promotions', ['promotion' => $promotion]);
    }

    public function addPromotion()
    {
        $this->ensureLoggedIn();

        $pourcentage = (float) $this->request->getPost('pourcentage');
        $date_expiration = trim($this->request->getPost('date_expiration'));

        if (! empty($pourcentage) && ! empty($date_expiration)) {
            $this->promotionModel->insert([
                'pourcentage' => $pourcentage,
                'date_expiration' => $date_expiration,
            ]);
        }

        return redirect()->to('/admin/promotions')->with('success', 'Promotion ajoutee.');
    }

    //quand il y a une promotion active , on reduit le montant de frais transfert pour chaque tranche de meme operateur selon le pourcentage

    public function applyPromotion(float $montant, string $operateur): float
    {
        $promotion = $this->promotionModel->getPromotion();

        if ($promotion) {
            $pourcentage = (float) $promotion['pourcentage'];
            $montantReduit = $montant * (1 - ($pourcentage / 100));
            return round($montantReduit, 2);
        }

        return $montant;
    }
}
