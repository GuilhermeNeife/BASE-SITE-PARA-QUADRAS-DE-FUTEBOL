<?php
/**
 * Página de Detalhe de Ambiente
 * Exibe galeria completa, descrição e informações do ambiente
 */

require_once 'config.php';

// Obter ID do ambiente
$env_id = (int)($_GET['id'] ?? 0);

if ($env_id <= 0) {
    header('Location: index.php');
    exit;
}

// Obter dados do ambiente
$env = fetch_assoc(query("SELECT * FROM environments WHERE id=$env_id AND visible=1"));

if (!$env) {
    header('Location: index.php');
    exit;
}

// Obter imagens do ambiente
$images = fetch_all(query("SELECT * FROM environment_images WHERE environment_id=$env_id ORDER BY `order`"));

// Obter dados gerais
$site = fetch_assoc(query("SELECT * FROM site_settings LIMIT 1"));
$nav_settings = fetch_assoc(query("SELECT * FROM nav_settings LIMIT 1"));
$environments = fetch_all(query("SELECT * FROM environments WHERE visible=1 ORDER BY `order` LIMIT 4"));

// Obter estilos das seções
$section_styles_result = fetch_all(query("SELECT * FROM section_styles"));
$section_styles = [];
foreach ($section_styles_result as $style) {
    $section_styles[$style['section_name']] = $style;
}

// Função auxiliar para obter cor da seção ou fallback
function get_section_style($section, $type, $fallback, $styles) {
    return $styles[$section][$type] ?? $fallback;
}

// Obter cores
$colors_result = fetch_all(query("SELECT color_name, color_value FROM theme_colors"));
$colors = [];
foreach ($colors_result as $c) {
    $colors[$c['color_name']] = $c['color_value'];
}

$color_black = $colors['Preto'] ?? '#0a0a0a';
$color_white = $colors['Branco'] ?? '#f5f5f0';
$color_green = $colors['Verde Principal'] ?? '#1a6b2e';
$color_green_light = $colors['Verde Claro'] ?? '#2d9e47';
$color_gray = $colors['Cinza'] ?? '#1c1c1c';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($env['name']); ?> - <?php echo htmlspecialchars($site['site_title']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: <?php echo $color_black; ?>;
            --white: <?php echo $color_white; ?>;
            --green: <?php echo $color_green; ?>;
            --green-light: <?php echo $color_green_light; ?>;
            --gray: <?php echo $color_gray; ?>;
            --nav-bg: <?php echo htmlspecialchars($nav_settings['nav_bg_color']); ?>;
            --nav-text: <?php echo htmlspecialchars($nav_settings['nav_text_color']); ?>;
            --nav-btn: <?php echo htmlspecialchars($nav_settings['nav_button_color']); ?>;
            --nav-btn-text: <?php echo htmlspecialchars($nav_settings['nav_button_text_color']); ?>;
            
            /* Estilos Dinâmicos */
            --gallery-bg: <?php echo get_section_style('gallery', 'bg_color', $color_black, $section_styles); ?>;
            --gallery-text: <?php echo get_section_style('gallery', 'text_color', $color_white, $section_styles); ?>;
            --gallery-accent: <?php echo get_section_style('gallery', 'accent_color', $color_green_light, $section_styles); ?>;
            
            --footer-bg: <?php echo get_section_style('footer', 'bg_color', $color_gray, $section_styles); ?>;
            --footer-text: <?php echo get_section_style('footer', 'text_color', $color_white, $section_styles); ?>;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--gallery-bg);
            color: var(--gallery-text);
            font-family: 'Montserrat', sans-serif;
        }

        /* NAVEGAÇÃO */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.2rem 4rem;
            background: var(--nav-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        
        .nav-logo {
            display: flex; align-items: center; gap: 12px;
        }
        
        .nav-logo-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.4rem;
            letter-spacing: 3px;
            color: var(--nav-text);
        }
        
        nav ul { list-style: none; display: flex; gap: 2.5rem; }
        nav ul a {
            color: var(--nav-text);
            text-decoration: none;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        
        nav ul a:hover { opacity: 1; }

        /* Menu Sanduíche */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            z-index: 1001;
        }

        .menu-toggle span {
            display: block;
            width: 25px;
            height: 3px;
            background: var(--nav-text);
            transition: 0.3s;
            border-radius: 3px;
        }

        .menu-toggle.active span:nth-child(1) { transform: translateY(8px) rotate(45deg); }
        .menu-toggle.active span:nth-child(2) { opacity: 0; }
        .menu-toggle.active span:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

        /* HERO DO AMBIENTE */
        .ambiente-hero {
            height: 60vh;
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            padding: 4rem;
            margin-top: 60px;
            background: var(--gallery-bg);
            overflow: hidden;
        }
        
        .ambiente-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('<?php echo UPLOAD_URL . htmlspecialchars($env['cover_image']); ?>');
            background-size: cover;
            background-position: center;
            opacity: 0.6;
        }
        
        .ambiente-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, var(--gallery-bg) 0%, transparent 100%);
            z-index: 1;
        }
        
        .ambiente-hero-content {
            position: relative;
            z-index: 2;
        }
        
        .ambiente-hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 4rem;
            letter-spacing: 3px;
            margin-bottom: 1rem;
        }
        
        .ambiente-hero p {
            font-size: 1.1rem;
            opacity: 0.95;
            max-width: 600px;
        }

        /* CONTEÚDO */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }
        
        .ambiente-description {
            background: rgba(255,255,255,0.05);
            padding: 3rem;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 4rem;
        }
        
        .ambiente-description h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--gallery-accent);
        }
        
        .ambiente-description p {
            font-size: 1rem;
            line-height: 1.8;
            color: rgba(255,255,255,0.8);
        }

        /* GALERIA */
        .gallery-section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            height: 300px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .gallery-item:hover {
            transform: translateY(-10px);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* OUTROS AMBIENTES */
        .other-environments {
            background: rgba(255,255,255,0.02);
            padding: 3rem;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .other-environments h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .environments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        
        .env-card {
            background: rgba(255,255,255,0.05);
            padding: 1.5rem;
            border-radius: 6px;
            text-align: center;
            transition: all 0.3s;
            text-decoration: none;
            color: var(--white);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .env-card:hover {
            background: rgba(255,255,255,0.1);
            border-color: var(--green-light);
            transform: translateY(-5px);
        }
        
        .env-card-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .env-card-name {
            font-weight: 700;
            font-size: 1rem;
        }

        /* RODAPÉ */
        footer {
            background: var(--footer-bg);
            color: var(--footer-text);
            padding: 2rem;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85rem;
        }

        /* RESPONSIVO */
        @media (max-width: 768px) {
            nav { padding: 1rem 1.5rem; }
            
            .menu-toggle { display: flex; }

            nav ul {
                position: fixed;
                top: 0;
                right: -100%;
                width: 80%;
                height: 100vh;
                background: var(--nav-bg);
                backdrop-filter: blur(20px);
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 2rem;
                transition: 0.4s;
                z-index: 1000;
                box-shadow: -10px 0 30px rgba(0,0,0,0.5);
            }

            nav ul.active { right: 0; }
            nav ul a { font-size: 1rem; }

            .ambiente-hero { height: auto; min-height: 50vh; padding: 100px 1.5rem 40px; }
            .ambiente-hero h1 { font-size: 2.5rem; }
            .ambiente-hero p { font-size: 0.9rem; }
            
            .container { padding: 2rem 1.5rem; }
            .ambiente-description { padding: 2rem 1.5rem; }
            
            .gallery-grid { grid-template-columns: 1fr; gap: 1rem; }
            .gallery-item { height: 250px; }
            
            .environments-grid { grid-template-columns: 1fr; }
            .other-environments { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <!-- NAVEGAÇÃO -->
    <nav>
        <div class="nav-logo">
            <div class="nav-logo-text">
                <?php echo htmlspecialchars($site['logo_text']); ?>
            </div>
        </div>
        <div class="menu-toggle" id="mobile-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul id="nav-list">
            <li><a href="index.php">← Voltar</a></li>
        </ul>
    </nav>

    <!-- HERO DO AMBIENTE -->
    <div class="ambiente-hero">
        <div class="ambiente-hero-content">
            <h1><?php echo htmlspecialchars($env['icon']); ?> <?php echo htmlspecialchars($env['name']); ?></h1>
            <p><?php echo htmlspecialchars($env['description']); ?></p>
        </div>
    </div>

    <!-- CONTEÚDO -->
    <div class="container">
        <!-- DESCRIÇÃO -->
        <?php if ($env['description']): ?>
            <div class="ambiente-description">
                <h2>Sobre este Ambiente</h2>
                <p><?php echo htmlspecialchars($env['description']); ?></p>
            </div>
        <?php endif; ?>

        <!-- GALERIA -->
        <?php if (!empty($images)): ?>
            <div class="gallery-section">
                <h2>Galeria de Fotos</h2>
                <div class="gallery-grid">
                    <?php foreach ($images as $img): ?>
                        <div class="gallery-item">
                            <img src="<?php echo UPLOAD_URL . htmlspecialchars($img['image_path']); ?>" alt="<?php echo htmlspecialchars($img['title']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- OUTROS AMBIENTES -->
        <div class="other-environments">
            <h3>Conheça Outros Ambientes</h3>
            <div class="environments-grid">
                <?php foreach ($environments as $other_env): ?>
                    <?php if ($other_env['id'] != $env_id): ?>
                        <a href="ambiente.php?id=<?php echo $other_env['id']; ?>" class="env-card">
                            <div class="env-card-icon"><?php echo htmlspecialchars($other_env['icon']); ?></div>
                            <div class="env-card-name"><?php echo htmlspecialchars($other_env['name']); ?></div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- RODAPÉ -->
    <footer>
        <p>&copy; 2024 <?php echo htmlspecialchars($site['site_title']); ?> - Todos os direitos reservados</p>
    </footer>
    <script>
        const mobileMenu = document.getElementById('mobile-menu');
        const navList = document.getElementById('nav-list');

        mobileMenu.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            navList.classList.toggle('active');
            document.body.style.overflow = navList.classList.contains('active') ? 'hidden' : 'initial';
        });
    </script>
</body>
</html>
