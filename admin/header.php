<?php
session_start();
require_once '../config.php';
require_once '../auth.php';
require_login();

$site = fetch_assoc(query("SELECT * FROM site_settings LIMIT 1"));
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo htmlspecialchars($site['site_title']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="../css/admin_new.css">
</head>
<body>
    <div class="admin-wrapper">
        <div class="overlay" id="overlay"></div>
        
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <span>⚽ SOCCER</span>
                <button class="menu-toggle" style="color:white; display:none;" id="close-sidebar">✕</button>
            </div>
            
            <nav class="sidebar-menu">
                <div class="menu-header">Geral</div>
                <a href="index.php" class="menu-item <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">🏠 Configurações</a>
                <a href="colors.php" class="menu-item <?php echo $current_page === 'colors.php' ? 'active' : ''; ?>">🎨 Cores do Site</a>
                
                <div class="menu-header">Conteúdo</div>
                <a href="hero.php" class="menu-item <?php echo $current_page === 'hero.php' ? 'active' : ''; ?>">🎯 Seção Hero</a>
                <a href="stats.php" class="menu-item <?php echo $current_page === 'stats.php' ? 'active' : ''; ?>">📊 Estatísticas</a>
                <a href="testimonials.php" class="menu-item <?php echo $current_page === 'testimonials.php' ? 'active' : ''; ?>">⭐ Depoimentos</a>
                <a href="environments.php" class="menu-item <?php echo $current_page === 'environments.php' ? 'active' : ''; ?>">🏢 Ambientes</a>
                <a href="contact.php" class="menu-item <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>">📞 Contato</a>
            </nav>
            
            <div class="sidebar-footer">
                <a href="../logout.php" class="logout-btn">🚪 Sair do Painel</a>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main-content">
            <header class="top-bar">
                <button class="menu-toggle" id="open-sidebar">☰</button>
                <div style="font-weight: 600; color: var(--primary-dark);">
                    <?php 
                        $titles = [
                            'index.php' => 'Configurações Gerais',
                            'colors.php' => 'Gerenciar Cores',
                            'hero.php' => 'Editar Seção Hero',
                            'stats.php' => 'Estatísticas do Site',
                            'testimonials.php' => 'Depoimentos de Clientes',
                            'environments.php' => 'Gerenciar Ambientes',
                            'contact.php' => 'Informações de Contato'
                        ];
                        echo $titles[$current_page] ?? 'Painel Administrativo';
                    ?>
                </div>
                <div style="font-size: 13px; color: var(--text-light); display: flex; align-items: center; gap: 8px;">
                    <span style="width: 8px; height: 8px; background: #2ecc71; border-radius: 50%;"></span>
                    Online
                </div>
            </header>
            
            <div class="content-body">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'success'; ?>">
                        <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
                    </div>
                <?php endif; ?>
