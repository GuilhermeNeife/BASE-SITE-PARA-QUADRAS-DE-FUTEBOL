<?php 
require_once 'header.php'; 
$nav = fetch_assoc(query("SELECT * FROM nav_settings LIMIT 1"));
$sections = fetch_all(query("SELECT * FROM section_styles"));

// Helper para pegar estilo de seção específica
function getSectionStyle($sections, $name) {
    foreach($sections as $s) {
        if($s['section_name'] == $name) return $s;
    }
    return ['bg_color' => '#ffffff', 'text_color' => '#000000', 'accent_color' => '#ff0000', 'bg_type' => 'color', 'overlay_opacity' => 0];
}

$hero_style = getSectionStyle($sections, 'hero');

$section_names = [
    'stats' => ['title' => '📊 Estatísticas', 'icon' => '📊'],
    'gallery' => ['title' => '🏢 Galeria de Ambientes', 'icon' => '🏢'],
    'testimonials' => ['title' => '⭐ Depoimentos', 'icon' => '⭐'],
    'contact' => ['title' => '📞 Contato', 'icon' => '📞'],
    'footer' => ['title' => '🔚 Rodapé', 'icon' => '🔚']
];
?>

<style>
    .preview-box {
        margin-top: 15px;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
        text-align: center;
        transition: 0.3s;
    }
    .preview-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 10px;
        display: block;
        color: #999;
    }
    .preview-accent {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: 600;
        margin-left: 5px;
    }
</style>

<!-- NAVEGAÇÃO -->
<div class="card">
    <div class="card-title">🎨 Menu de Navegação</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_nav_colors">
        <input type="hidden" name="redirect_to" value="colors">
        
        <div class="grid-2">
            <div class="form-group">
                <label>Fundo do Menu</label>
                <input type="color" name="nav_bg_color" class="form-control" style="height: 45px;" value="<?php echo $nav['nav_bg_color']; ?>">
            </div>
            <div class="form-group">
                <label>Cor do Texto</label>
                <input type="color" name="nav_text_color" class="form-control" style="height: 45px;" value="<?php echo $nav['nav_text_color']; ?>">
            </div>
            <div class="form-group">
                <label>Cor do Botão</label>
                <input type="color" name="nav_button_color" class="form-control" style="height: 45px;" value="<?php echo $nav['nav_button_color']; ?>">
            </div>
            <div class="form-group">
                <label>Texto do Botão</label>
                <input type="color" name="nav_button_text_color" class="form-control" style="height: 45px;" value="<?php echo $nav['nav_button_text_color']; ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar Menu</button>
    </form>
</div>

<!-- HERO -->
<div class="card">
    <div class="card-title">🎯 Hero (Banner Principal)</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_section_style">
        <input type="hidden" name="section_name" value="hero">
        
        <div class="grid-2">
            <div class="form-group">
                <label>Tipo de Fundo</label>
                <select name="bg_type" class="form-control">
                    <option value="color" <?php echo $hero_style['bg_type'] == 'color' ? 'selected' : ''; ?>>Cor Sólida</option>
                    <option value="gradient" <?php echo $hero_style['bg_type'] == 'gradient' ? 'selected' : ''; ?>>Gradiente</option>
                    <option value="image" <?php echo $hero_style['bg_type'] == 'image' ? 'selected' : ''; ?>>Imagem</option>
                </select>
            </div>
            <div class="form-group">
                <label>Cor de Fundo</label>
                <input type="color" name="bg_color" class="form-control" style="height: 45px;" value="<?php echo $hero_style['bg_color']; ?>">
            </div>
            <div class="form-group">
                <label>Opacidade do Overlay (0-100)</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="range" name="overlay_opacity" min="0" max="100" class="form-control" style="flex: 1; padding: 0;" value="<?php echo $hero_style['overlay_opacity']; ?>" oninput="this.nextElementSibling.value = this.value + '%'">
                    <output style="width: 45px; font-weight: 600;"><?php echo $hero_style['overlay_opacity']; ?>%</output>
                </div>
            </div>
            <div class="form-group">
                <label>Cor de Texto</label>
                <input type="color" name="text_color" class="form-control" style="height: 45px;" value="<?php echo $hero_style['text_color']; ?>">
            </div>
            <div class="form-group">
                <label>Cor de Destaque</label>
                <input type="color" name="accent_color" class="form-control" style="height: 45px;" value="<?php echo $hero_style['accent_color']; ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar Estilo Hero</button>
    </form>
</div>

<!-- SEÇÕES DINÂMICAS -->
<?php foreach($section_names as $id => $info): 
    $style = getSectionStyle($sections, $id);
?>
<div class="card" id="section-<?php echo $id; ?>">
    <div class="card-title"><?php echo $info['title']; ?></div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_section_style">
        <input type="hidden" name="section_name" value="<?php echo $id; ?>">
        <input type="hidden" name="bg_type" value="color">
        <input type="hidden" name="overlay_opacity" value="0">
        
        <div class="grid-2">
            <div class="form-group">
                <label>Cor de Fundo</label>
                <input type="color" name="bg_color" class="form-control color-input" data-target="bg" style="height: 45px;" value="<?php echo $style['bg_color']; ?>">
            </div>
            <div class="form-group">
                <label>Cor de Texto</label>
                <input type="color" name="text_color" class="form-control color-input" data-target="text" style="height: 45px;" value="<?php echo $style['text_color']; ?>">
            </div>
            <div class="form-group">
                <label>Cor de Destaque</label>
                <input type="color" name="accent_color" class="form-control color-input" data-target="accent" style="height: 45px;" value="<?php echo $style['accent_color']; ?>">
            </div>
            
            <div class="form-group">
                <span class="preview-label">Preview</span>
                <div class="preview-box" style="background-color: <?php echo $style['bg_color']; ?>; color: <?php echo $style['text_color']; ?>;">
                    Texto de Exemplo 
                    <span class="preview-accent" style="background-color: <?php echo $style['accent_color']; ?>; color: #fff;">Destaque</span>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar Seção</button>
    </form>
</div>
<?php endforeach; ?>

<script>
    // Preview em tempo real
    document.querySelectorAll('.color-input').forEach(input => {
        input.addEventListener('input', function() {
            const card = this.closest('.card');
            const preview = card.querySelector('.preview-box');
            const accent = card.querySelector('.preview-accent');
            const target = this.dataset.target;
            const value = this.value;

            if (target === 'bg') {
                preview.style.backgroundColor = value;
            } else if (target === 'text') {
                preview.style.color = value;
            } else if (target === 'accent') {
                accent.style.backgroundColor = value;
            }
        });
    });
</script>

<?php require_once 'footer.php'; ?>
