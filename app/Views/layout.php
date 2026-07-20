<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Airtel Money' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --mm-primary: #2563eb;
            --mm-primary-dark: #1d4ed8;
            --mm-primary-light: #dbeafe;
            --mm-success: #16a34a;
            --mm-success-light: #dcfce7;
            --mm-warning: #f59e0b;
            --mm-danger: #dc2626;
            --mm-danger-light: #fee2e2;
            --mm-info: #0891b2;
            --mm-dark: #1e293b;
            --mm-gray: #64748b;
            --mm-light: #f1f5f9;
            --mm-white: #ffffff;
            --mm-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
            --mm-shadow-md: 0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.06);
            --mm-shadow-lg: 0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.05);
            --mm-radius: 12px;
            --mm-radius-sm: 8px;
            --mm-radius-lg: 16px;
            --mm-transition: all 0.2s ease;
        }

        * { font-family: 'Inter', sans-serif; }

        body {
            background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 50%, #f0fdf4 100%);
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .mm-navbar {
            background: linear-gradient(135deg, var(--mm-dark) 0%, #0f172a 100%) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 0.8rem 0;
            border-bottom: 3px solid var(--mm-primary);
        }
        .mm-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.025em;
        }
        .mm-navbar .navbar-brand i {
            color: var(--mm-primary);
            margin-right: 6px;
        }

        /* ===== CARDS ===== */
        .mm-card {
            background: var(--mm-white);
            border: none;
            border-radius: var(--mm-radius);
            box-shadow: var(--mm-shadow);
            transition: var(--mm-transition);
            overflow: hidden;
        }
        .mm-card:hover {
            box-shadow: var(--mm-shadow-md);
        }
        .mm-card-header {
            background: linear-gradient(135deg, var(--mm-primary) 0%, var(--mm-primary-dark) 100%);
            color: white;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        /* ===== SIDEBAR CLIENT ===== */
        .client-sidebar {
            background: var(--mm-white);
            border-radius: var(--mm-radius);
            box-shadow: var(--mm-shadow);
            overflow: hidden;
        }
        .sidebar-header {
            background: linear-gradient(135deg, var(--mm-dark) 0%, #0f172a 100%);
            color: white;
            padding: 1rem 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-header i { font-size: 1.2rem; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1.25rem;
            color: var(--mm-gray);
            text-decoration: none;
            font-weight: 500;
            transition: var(--mm-transition);
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            color: var(--mm-primary);
            background: var(--mm-primary-light);
            border-left-color: var(--mm-primary);
        }
        .sidebar-link.active {
            color: var(--mm-primary);
            background: var(--mm-primary-light);
            border-left-color: var(--mm-primary);
            font-weight: 600;
        }
        .sidebar-link i { font-size: 1.1rem; width: 20px; text-align: center; }

        /* ===== SOLDE CARD ===== */
        .solde-card {
            background: linear-gradient(135deg, var(--mm-primary) 0%, #7c3aed 100%);
            border-radius: var(--mm-radius-lg);
            padding: 2.5rem 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(37,99,235,0.3);
        }
        .solde-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .solde-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .solde-card .solde-label {
            font-size: 0.875rem;
            opacity: 0.85;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .solde-card .solde-montant {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            position: relative;
            z-index: 1;
        }

        /* ===== FORMS ===== */
        .form-control, .form-select {
            border-radius: var(--mm-radius-sm);
            border: 2px solid #e2e8f0;
            transition: var(--mm-transition);
            padding: 0.65rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--mm-primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .form-label {
            font-weight: 600;
            color: var(--mm-dark);
            font-size: 0.875rem;
            margin-bottom: 0.35rem;
        }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: var(--mm-radius-sm);
            font-weight: 600;
            transition: var(--mm-transition);
            padding: 0.6rem 1.5rem;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary {
            background: linear-gradient(135deg, var(--mm-primary) 0%, var(--mm-primary-dark) 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        .btn-success {
            background: linear-gradient(135deg, var(--mm-success) 0%, #15803d 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(22,163,74,0.3);
        }
        .btn-lg { padding: 0.8rem 2rem; font-size: 1.05rem; }

        /* ===== BADGES ===== */
        .badge {
            font-weight: 600;
            padding: 0.35em 0.75em;
            border-radius: 6px;
        }

        /* ===== TABLES ===== */
        .table {
            border-radius: var(--mm-radius-sm);
            overflow: hidden;
        }
        .table thead th {
            background: var(--mm-light);
            color: var(--mm-dark);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 2px solid #e2e8f0;
            padding: 0.75rem;
        }
        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-hover tbody tr:hover {
            background: rgba(37,99,235,0.03);
        }

        /* ===== FLASH MESSAGES ===== */
        .flash-alert {
            animation: slideDown 0.4s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .flash-alert .btn-close { filter: none; }

        /* ===== SUMMARY CARDS ===== */
        .summary-box {
            border-radius: var(--mm-radius-sm);
            padding: 1rem;
            text-align: center;
            transition: var(--mm-transition);
        }
        .summary-box:hover { transform: scale(1.02); }
        .summary-box small { font-size: 0.7rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.05em; }
        .summary-box .value { font-size: 1.25rem; font-weight: 700; display: block; margin-top: 2px; }

        /* ===== CHECKBOX V2 ===== */
        .form-check-input:checked {
            background-color: var(--mm-primary);
            border-color: var(--mm-primary);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.5s ease; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .solde-card .solde-montant { font-size: 1.75rem; }
            .sidebar-link { padding: 0.6rem 1rem; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mm-navbar mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">
            <i class="bi bi-phone-vibrate"></i>Airtel Money
        </a>
        <?php if (session()->get('isClientLoggedIn')): ?>
            <div class="d-flex align-items-center gap-2">
                <span class="text-light small d-none d-md-inline">
                    <i class="bi bi-telephone"></i> <?= esc(session()->get('client_telephone')) ?>
                </span>
                <a href="<?= site_url('client/logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Deconnexion
                </a>
            </div>
        <?php elseif (session()->get('isAdminLoggedIn')): ?>
            <div class="d-flex align-items-center gap-2">
                <span class="text-light small d-none d-md-inline">
                    <i class="bi bi-shield-lock"></i> Admin
                </span>
                <a href="<?= site_url('admin/logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Deconnexion
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container pb-5">
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger flash-alert alert-dismissible fade show" role="alert" id="flashAlert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success flash-alert alert-dismissible fade show" role="alert" id="flashAlert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const flash = document.getElementById('flashAlert');
    if (flash) {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(flash);
            bsAlert.close();
        }, 4000);
    }
});
</script>
</body>
</html>
