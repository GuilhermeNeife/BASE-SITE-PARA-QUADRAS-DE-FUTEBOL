<?php
/**
 * Página Pública - Site Principal
 * Consome dados do banco de dados e aplica cores/configurações personalizadas
 */

require_once 'config.php';

// Obter dados
$site = fetch_assoc(query("SELECT * FROM site_settings LIMIT 1"));
$nav_settings = fetch_assoc(query("SELECT * FROM nav_settings LIMIT 1"));
$hero = fetch_assoc(query("SELECT * FROM hero_section LIMIT 1"));
$stats = fetch_all(query("SELECT * FROM statistics ORDER BY `order`"));
$testimonials = fetch_all(query("SELECT * FROM testimonials ORDER BY `order`"));
$contact = fetch_assoc(query("SELECT * FROM contact_info LIMIT 1"));
$environments = fetch_all(query("SELECT * FROM environments WHERE visible=1 ORDER BY `order`"));

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

// Valores padrão se não existirem cores
$color_black = $colors['Preto'] ?? '#0a0a0a';
$color_white = $colors['Branco'] ?? '#f5f5f0';
$color_green = $colors['Verde Principal'] ?? '#1a6b2e';
$color_green_light = $colors['Verde Claro'] ?? '#2d9e47';
$color_gray = $colors['Cinza'] ?? '#1c1c1c';
$color_gray_mid = $colors['Cinza Médio'] ?? '#2e2e2e';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site['site_title']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;600;700;900&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: <?php echo $color_black; ?>;
            --white: <?php echo $color_white; ?>;
            --green: <?php echo $color_green; ?>;
            --green-light: <?php echo $color_green_light; ?>;
            --gray: <?php echo $color_gray; ?>;
            --gray-mid: <?php echo $color_gray_mid; ?>;
            --nav-bg: <?php echo htmlspecialchars($nav_settings['nav_bg_color']); ?>;
            --nav-text: <?php echo htmlspecialchars($nav_settings['nav_text_color']); ?>;
            --nav-btn: <?php echo htmlspecialchars($nav_settings['nav_button_color']); ?>;
            --nav-btn-text: <?php echo htmlspecialchars($nav_settings['nav_button_text_color']); ?>;
            
            /* Estilos Dinâmicos por Seção */
            --hero-bg: <?php echo get_section_style('hero', 'bg_color', '#0a0a0a', $section_styles); ?>;
            --hero-text: <?php echo get_section_style('hero', 'text_color', '#ffffff', $section_styles); ?>;
            --hero-accent: <?php echo get_section_style('hero', 'accent_color', '#2d9e47', $section_styles); ?>;
            --hero-overlay: <?php echo (get_section_style('hero', 'overlay_opacity', 40, $section_styles) / 100); ?>;
            
            --stats-bg: <?php echo get_section_style('stats', 'bg_color', '#ff0000', $section_styles); ?>;
            --stats-text: <?php echo get_section_style('stats', 'text_color', '#ffffff', $section_styles); ?>;
            --stats-accent: <?php echo get_section_style('stats', 'accent_color', '#ff0000', $section_styles); ?>;
            
            --gallery-bg: <?php echo get_section_style('gallery', 'bg_color', '#ffffff', $section_styles); ?>;
            --gallery-text: <?php echo get_section_style('gallery', 'text_color', '#000000', $section_styles); ?>;
            --gallery-accent: <?php echo get_section_style('gallery', 'accent_color', '#ff0000', $section_styles); ?>;
            
            --testimonials-bg: <?php echo get_section_style('testimonials', 'bg_color', '#0a0a0a', $section_styles); ?>;
            --testimonials-text: <?php echo get_section_style('testimonials', 'text_color', '#f5f5f0', $section_styles); ?>;
            --testimonials-accent: <?php echo get_section_style('testimonials', 'accent_color', '#ff0000', $section_styles); ?>;
            
            --contact-bg: <?php echo get_section_style('contact', 'bg_color', '#0a0a0a', $section_styles); ?>;
            --contact-text: <?php echo get_section_style('contact', 'text_color', '#f5f5f0', $section_styles); ?>;
            --contact-accent: <?php echo get_section_style('contact', 'accent_color', '#ff0000', $section_styles); ?>;

            --footer-bg: <?php echo get_section_style('footer', 'bg_color', '#0a0a0a', $section_styles); ?>;
            --footer-text: <?php echo get_section_style('footer', 'text_color', '#ffffff', $section_styles); ?>;
            --footer-accent: <?php echo get_section_style('footer', 'accent_color', '#ff0000', $section_styles); ?>;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--black);
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }

        /* =====================================================
           NAVEGAÇÃO
           ===================================================== */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.2rem 4rem;
            background: var(--nav-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            transition: all 0.3s;
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
        
        .nav-logo-text span {
            font-family: 'Dancing Script', cursive;
            font-size: 1.1rem;
            color: var(--green-light);
            display: block;
            line-height: 0.9;
        }
        
        nav ul { list-style: none; display: flex; gap: 2.5rem; }
        nav ul a {
            color: var(--nav-text);
            text-decoration: none;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            transition: color 0.3s;
            opacity: 0.8;
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

        /* =====================================================
           HERO
           ===================================================== */
        .hero {
            height: 100vh;
            position: relative;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            margin-top: 60px;
        }
        
        .hero-bg {
            position: absolute; inset: 0;
            <?php 
            $hero_bg_type = get_section_style('hero', 'bg_type', 'image', $section_styles);
            if ($hero_bg_type === 'image' && $hero['hero_image']): ?>
                background-image: url('<?php echo UPLOAD_URL . htmlspecialchars($hero['hero_image']); ?>');
                background-size: cover;
                background-position: center;
            <?php elseif ($hero_bg_type === 'gradient'): ?>
                background: linear-gradient(135deg, var(--hero-bg) 0%, #111 100%);
            <?php else: ?>
                background: var(--hero-bg);
            <?php endif; ?>
        }
        
        .hero-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,var(--hero-overlay));
        }
        
        .hero-content {
            position: relative; z-index: 2;
            text-align: center;
        }
        
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.68rem; letter-spacing: 4px; text-transform: uppercase;
            color: <?php echo $hero['badge_color'] ?? 'var(--hero-accent)'; ?>;
            border: 1px solid <?php echo $hero['badge_color'] ?? 'var(--hero-accent)'; ?>;
            padding: 0.45rem 1.2rem;
            border-radius: 2px;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(5rem, 13vw, 11rem);
            letter-spacing: 6px;
            line-height: 0.88;
            color: <?php echo $hero['title_color'] ?? 'var(--hero-text)'; ?>;
        }
        
        .hero h1 em {
            font-family: 'Dancing Script', cursive;
            font-style: normal;
            color: <?php echo $hero['emphasis_color'] ?? 'var(--hero-accent)'; ?>;
            display: block;
            font-size: 0.42em;
            letter-spacing: 2px;
            margin-top: 0.3em;
        }
        
        .hero-sub {
            margin-top: 2rem;
            font-size: 0.8rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(245,245,240,0.45);
            font-weight: 400;
        }
        
        .hero-actions {
            margin-top: 2.8rem;
            display: flex; gap: 1rem; justify-content: center;
        }
        
        .btn-primary {
            background: var(--nav-btn);
            color: var(--nav-btn-text);
            padding: 0.9rem 2.4rem;
            border: none; cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 2px;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-primary:hover { transform: translateY(-2px); }

        /* =====================================================
           STATS BAR
           ===================================================== */
        .stats-bar {
            background: var(--stats-bg);
            color: var(--stats-text);
            padding: 1.5rem 4rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .stat-item {
            text-align: center;
            border-right: 1px solid rgba(255,255,255,0.2);
            padding: 0.5rem;
        }
        
        .stat-item:last-child { border-right: none; }
        
        .stat-number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            color: var(--stats-text);
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--stats-text);
            opacity: 0.7;
            margin-top: 5px;
        }

        /* =====================================================
           SEÇÕES GERAIS
           ===================================================== */
        section { padding: 8rem 4rem; position: relative; }
        
        .section-tag {
            font-size: 0.65rem; letter-spacing: 4px;
            color: var(--green-light);
            margin-bottom: 1rem; font-weight: 700;
            text-transform: uppercase;
        }
        
        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 4rem; letter-spacing: 4px;
            line-height: 1; margin-bottom: 1.5rem;
        }
        
        .section-title em {
            font-family: 'Dancing Script', cursive;
            font-style: normal; color: var(--green-light);
            font-size: 0.6em; margin-left: 10px;
        }
        
        .section-body {
            max-width: 600px; font-size: 0.9rem;
            line-height: 1.8; color: rgba(255,255,255,0.6);
            margin-bottom: 4rem;
        }

        /* =====================================================
           GALERIA
           ===================================================== */
        #galeria { background: var(--gallery-bg); color: var(--gallery-text); }
        .gallery-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .gallery-card {
            position: relative; height: 400px; overflow: hidden;
            border-radius: 4px; cursor: pointer;
        }
        .gallery-card img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .gallery-card:hover img { transform: scale(1.08); }
        .gallery-card-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, transparent 60%);
            display: flex; flex-direction: column; justify-content: flex-end;
            padding: 2rem;
        }
        .gallery-card-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem;
            letter-spacing: 2px; color: #fff; margin-bottom: 0.5rem;
        }
        .gallery-card-desc {
            font-size: 0.75rem; color: rgba(255,255,255,0.7);
            line-height: 1.5;
        }

        /* =====================================================
           DEPOIMENTOS
           ===================================================== */
        #depoimentos { 
            background: var(--testimonials-bg); 
            color: var(--testimonials-text); 
            position: relative;
            overflow: hidden;
        }
        
        .testimonials-overlay-bg {
            position: absolute; inset: 0;
            background: rgba(0,0,0,var(--testimonials-overlay));
            pointer-events: none;
        }
        
        .testimonials-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px; position: relative; z-index: 1;
        }
        
        .testimonial-card {
            background: rgba(255,255,255,0.03);
            padding: 3rem; border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s;
            display: flex; flex-direction: column;
        }
        
        .testimonial-card:hover {
            background: rgba(255,255,255,0.05);
            transform: translateY(-10px);
            border-color: var(--testimonials-accent);
        }
        
        .stars { color: var(--testimonials-accent); margin-bottom: 1.5rem; font-size: 0.8rem; }
        
        .testimonial-text {
            font-size: 1rem; line-height: 1.8; font-style: italic;
            margin-bottom: 2rem; color: var(--testimonials-text);
            opacity: 0.9; flex-grow: 1;
        }
        
        .testimonial-author {
            font-weight: 700; font-size: 0.9rem; letter-spacing: 1px;
            text-transform: uppercase; color: var(--testimonials-accent);
        }
        
        .testimonial-role {
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;
            opacity: 0.5; margin-top: 5px;
        }

        /* =====================================================
           CONTATO
           ===================================================== */
        #contato { background: var(--contact-bg); color: var(--contact-text); }
        .contact-info {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }
        .contact-item-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.5rem;
            letter-spacing: 2px; color: var(--contact-accent);
            margin-bottom: 1rem;
        }
        .contact-item-content {
            font-size: 0.9rem; line-height: 1.8; opacity: 0.7;
        }

        /* =====================================================
           RODAPÉ
           ===================================================== */
        footer {
            background: var(--footer-bg); color: var(--footer-text);
            padding: 4rem; text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        footer p { font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; opacity: 0.6; }

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

            .hero { height: auto; min-height: 80vh; padding: 100px 1.5rem 60px; }
            .hero h1 { font-size: 3.5rem; }
            .hero-sub { font-size: 0.7rem; letter-spacing: 2px; }
            .hero-actions { flex-direction: column; width: 100%; max-width: 300px; margin: 2rem auto 0; }
            
            section { padding: 5rem 1.5rem; }
            .section-title { font-size: 2.8rem; }
            
            .stats-bar { grid-template-columns: repeat(2, 1fr); padding: 2rem 1.5rem; gap: 20px; }
            .stat-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; }
            .stat-item:nth-child(even) { border-right: none; }
            .stat-item:last-child { border-bottom: none; }
            
            .gallery-grid { grid-template-columns: 1fr; }
            .gallery-card { height: 300px; }
            
            .testimonials-grid { grid-template-columns: 1fr; }
            .testimonial-card { padding: 2rem; }
            
            .contact-info { grid-template-columns: 1fr; gap: 30px; }
            
            footer { padding: 3rem 1.5rem; }
        }
    </style>
</head>
<body>
    <!-- NAVEGAÇÃO -->
    <nav>
        <div class="nav-logo">
            <?php if ($site['use_logo_image'] && $site['logo_image']): ?>
                <img src="<?php echo UPLOAD_URL . htmlspecialchars($site['logo_image']); ?>" alt="Logo" style="height: 40px;">
            <?php else: ?>
                <div class="nav-logo-text">
                    <?php echo htmlspecialchars($site['logo_text']); ?>
                    <span><?php echo htmlspecialchars($site['logo_tagline']); ?></span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="menu-toggle" id="mobile-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        
        <ul id="nav-list">
            <li><a href="#hero">Home</a></li>
            <li><a href="#galeria">Ambientes</a></li>
            <?php if ($site['show_testimonials'] ?? 1): ?>
                <li><a href="#depoimentos">Depoimentos</a></li>
            <?php endif; ?>
            <li><a href="#contato">Contato</a></li>
        </ul>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">
                ⭐ <?php echo htmlspecialchars($hero['badge']); ?>
            </div>
            <h1>
                <?php echo htmlspecialchars($hero['title']); ?>
                <em><?php echo htmlspecialchars($hero['title_emphasis']); ?></em>
            </h1>
            <p class="hero-sub"><?php echo htmlspecialchars($hero['description']); ?></p>
            <div class="hero-actions">
                <button class="btn-primary"><?php echo htmlspecialchars($hero['cta_button1']); ?></button>
                <button class="btn-primary" style="background: transparent; border: 2px solid var(--nav-btn-text);"><?php echo htmlspecialchars($hero['cta_button2']); ?></button>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <div class="stats-bar" style="background: var(--stats-bg); color: var(--stats-text);">
        <?php foreach ($stats as $stat): ?>
            <div class="stat-item">
                <div class="stat-number"><?php echo htmlspecialchars($stat['stat_number']); ?></div>
                <div class="stat-label"><?php echo htmlspecialchars($stat['stat_label']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- GALERIA DE AMBIENTES -->
    <section id="galeria" style="background: var(--gallery-bg); color: var(--gallery-text);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div class="section-tag" style="color: var(--gallery-accent);">🏢 CONHEÇA NOSSOS ESPAÇOS</div>
            <h2 class="section-title" style="color: var(--gallery-text);">Ambientes <em style="color: var(--gallery-accent);">Premium</em></h2>
            <p class="section-body" style="color: var(--gallery-text); opacity: 0.7;">Explore todos os nossos ambientes e conheça a infraestrutura completa da Soccer Society.</p>
        </div>
        
        <div class="gallery-grid">
            <?php foreach ($environments as $env): ?>
                <a href="ambiente.php?id=<?php echo $env['id']; ?>" style="text-decoration: none;">
                    <div class="gallery-card">
                        <img src="<?php echo $env['cover_image'] ? UPLOAD_URL . htmlspecialchars($env['cover_image']) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22%3E%3Crect fill=%22%23333%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'; ?>" alt="<?php echo htmlspecialchars($env['name']); ?>">
                        <div class="gallery-card-overlay">
                            <h3 class="gallery-card-title">
                                <?php echo htmlspecialchars($env['icon']); ?> <?php echo htmlspecialchars($env['name']); ?>
                            </h3>
                            <p class="gallery-card-desc"><?php echo htmlspecialchars(substr($env['description'], 0, 80)); ?>...</p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- DEPOIMENTOS -->
    <?php if ($site['show_testimonials'] ?? 1): ?>
    <section id="depoimentos" style="background: var(--testimonials-bg); color: var(--testimonials-text);">
        <div class="testimonials-overlay-bg"></div>
        <div style="max-width: 1200px; margin: 0 auto; position: relative; z-index: 1;">
            <div class="section-tag" style="color: var(--testimonials-accent);">⭐ O QUE DIZEM SOBRE NÓS</div>
            <h2 class="section-title" style="color: var(--testimonials-text);">Depoimentos de <em style="color: var(--testimonials-accent);">Clientes</em></h2>
        </div>
        
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $test): ?>
                <div class="testimonial-card">
                    <div class="stars"><?php echo str_repeat('⭐', $test['stars']); ?></div>
                    <p class="testimonial-text">"<?php echo htmlspecialchars($test['text']); ?>"</p>
                    <p class="testimonial-author"><?php echo htmlspecialchars($test['author_name']); ?></p>
                    <p class="testimonial-role"><?php echo htmlspecialchars($test['author_role']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- CONTATO -->
    <section id="contato" style="background: var(--contact-bg); padding: 8rem 4rem;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div class="section-tag" style="color: var(--contact-accent);">📞 ENTRE EM CONTATO</div>
            <h2 class="section-title" style="color: var(--contact-text);">Informações de <em style="color: var(--contact-accent);">Contato</em></h2>
        </div>
        
        <div class="contact-info">
            <div class="contact-item">
                <div class="contact-item-title" style="color: var(--contact-accent);">📍 Endereço</div>
                <div class="contact-item-content" style="color: var(--contact-text);"><?php echo htmlspecialchars($contact['address']); ?></div>
            </div>
            
            <div class="contact-item">
                <div class="contact-item-title" style="color: var(--contact-accent);">📞 Telefones</div>
                <div class="contact-item-content" style="color: var(--contact-text);">
                    <?php echo htmlspecialchars($contact['phone1']); ?><br>
                    <?php if ($contact['phone2']): echo htmlspecialchars($contact['phone2']); endif; ?>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-item-title" style="color: var(--contact-accent);">📧 Email</div>
                <div class="contact-item-content" style="color: var(--contact-text);"><?php echo htmlspecialchars($contact['email']); ?></div>
            </div>
            
            <div class="contact-item">
                <div class="contact-item-title" style="color: var(--contact-accent);">🕐 Horário</div>
                <div class="contact-item-content" style="color: var(--contact-text);">
                    <?php echo htmlspecialchars($contact['hours_start']); ?> às <?php echo htmlspecialchars($contact['hours_end']); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- RODAPÉ -->
    <footer style="background: var(--footer-bg); color: var(--footer-text); padding: 5rem 4rem 2rem; border-top: 1px solid rgba(255,255,255,0.05); text-align: center;">
        <p>&copy; 2024 <?php echo htmlspecialchars($site['site_title']); ?> - Todos os direitos reservados</p>
        <p style="margin-top: 10px; font-size: 0.8rem; color: var(--footer-accent);">Desenvolvido por Guilherme Neife</p>
    </footer>
    <script>
        const mobileMenu = document.getElementById('mobile-menu');
        const navList = document.getElementById('nav-list');
        const navLinks = document.querySelectorAll('#nav-list a');

        mobileMenu.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            navList.classList.toggle('active');
            document.body.style.overflow = navList.classList.contains('active') ? 'hidden' : 'initial';
        });

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                navList.classList.remove('active');
                document.body.style.overflow = 'initial';
            });
        });
    </script>
</body>
</html>
