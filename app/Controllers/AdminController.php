<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheMontantModel;
use App\Models\HistoriqueModel;
use App\Models\UtilisateurModel;

class AdminController extends BaseController
{
    protected AdminModel $adminModel;
    protected PrefixeModel $prefixeModel;
    protected TypeOperationModel $typeOperationModel;
    protected TrancheMontantModel $trancheModel;
    protected HistoriqueModel $historiqueModel;
    protected UtilisateurModel $utilisateurModel;

    public function __construct()
    {
        $this->adminModel        = new AdminModel();
        $this->prefixeModel      = new PrefixeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->trancheModel      = new TrancheMontantModel();
        $this->historiqueModel   = new HistoriqueModel();
        $this->utilisateurModel = new UtilisateurModel();
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
        $totalGeneral = array_sum(array_column($gains, 'total_frais'));

        return view('admin/dashboard', ['gains' => $gains, 'totalGeneral' => $totalGeneral]);
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
        if (! empty($code)) {
            $this->prefixeModel->insert(['code' => $code]);
        }

        return redirect()->to('/admin/prefixes')->with('success', 'Prefixe ajoute.');
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
}
