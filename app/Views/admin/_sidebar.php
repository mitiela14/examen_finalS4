<div class="list-group">
    <a href="<?= site_url('admin/dashboard') ?>" class="list-group-item list-group-item-action <?= (uri_string() === 'admin/dashboard') ? 'active' : '' ?>">Tableau de bord</a>
    <a href="<?= site_url('admin/clients') ?>" class="list-group-item list-group-item-action <?= (strpos(uri_string(), 'admin/clients') === 0) ? 'active' : '' ?>">Liste des clients</a>
    <a href="<?= site_url('admin/tranches') ?>" class="list-group-item list-group-item-action <?= (uri_string() === 'admin/tranches') ? 'active' : '' ?>">Gestion des tranches</a>
    <a href="<?= site_url('admin/prefixes') ?>" class="list-group-item list-group-item-action <?= (uri_string() === 'admin/prefixes') ? 'active' : '' ?>">Configuration prefixes</a>
    <a href="<?= site_url('admin/commissions') ?>" class="nav-link">Commissions</a>
    <a href="<?= site_url('admin/promotions') ?>" class="list-group-item list-group-item-action <?= (uri_string() === 'admin/promotions') ? 'active' : '' ?>">Promotions</a>
</div>
