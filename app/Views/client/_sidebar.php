<?php
$currentPage = $currentPage ?? '';
?>
<div class="client-sidebar mb-4">
    <div class="sidebar-header">
        <i class="bi bi-person-circle"></i>
        <span>Mon compte</span>
    </div>
    <a href="<?= site_url('client/dashboard') ?>" class="sidebar-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-wallet2"></i> Solde
    </a>
    <a href="<?= site_url('client/operation') ?>" class="sidebar-link <?= $currentPage === 'operation' ? 'active' : '' ?>">
        <i class="bi bi-plus-circle"></i> Nouvelle operation
    </a>
    <a href="<?= site_url('client/historique') ?>" class="sidebar-link <?= $currentPage === 'historique' ? 'active' : '' ?>">
        <i class="bi bi-clock-history"></i> Historique
    </a>
    <a href="<?= site_url('client/profil') ?>" class="sidebar-link <?= $currentPage === 'profil' ? 'active' : '' ?>">
        <i class="bi bi-person-gear"></i> Mon profil
    </a>
    <a href="<?= site_url('client/epargne') ?>" class="sidebar-link <?= $currentPage === 'epargne' ? 'active' : '' ?>">
        <i class="bi bi-person-gear"></i> Mon compte epargne
    </a>
   
</div>
