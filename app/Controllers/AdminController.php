<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheMontantModel;
use App\Models\HistoriqueModel;
use App\Models\teurModel;

class AdminController extends BaseController
{
    protected AdminModel $adminModel;
    protected PrefixeModel $prefixeModel;
    protected TypeOperationModel $typeOperationModel;
    protected TrancheMontantModel $trancheModel;
    protected HistoriqueModel $historiqueModel;
    protected teurModel $teurModel;

    public function __construct()
    {
        $this->adminModel        = new AdminModel();
        $this->prefixeModel      = new PrefixeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->trancheModel      = new TrancheMontantModel();
        $this->historiqueModel   = new HistoriqueModel();
        $this->teurModel  = new teurModel();
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

        $clients = $this->teurModel->findAll();

        return view('admin/clients', ['clients' => $clients]);
    }

    public function clientDetail(int $id): string
    {
        $this->ensureLoggedIn();

        $client     = $this->teurModel->find($id);
        $historique = $this->historiqueModel->historiqueClient($id);

        return view('admin/client_detail', ['client' => $client, 'historique' => $historique]);
    }
}
