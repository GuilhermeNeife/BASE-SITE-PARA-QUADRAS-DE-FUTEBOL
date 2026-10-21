<?php 
require_once 'header.php'; 
$hero = fetch_assoc(query("SELECT * FROM hero_section LIMIT 1"));
?>

<div class="card">
    <div class="card-title">🎯 Conteúdo do Hero</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_hero">
        
        <div class="form-group">
            <label>Badge (Texto pequeno no topo)</label>
            <input type="text" name="badge" class="form-control" value="<?php echo htmlspecialchars($hero['badge']); ?>">
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Título Principal</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($hero['title']); ?>">
            </div>
            <div class="form-group">
                <label>Texto em Destaque (Ênfase)</label>
                <input type="text" name="title_emphasis" class="form-control" value="<?php echo htmlspecialchars($hero['title_emphasis']); ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Descrição / Subtítulo</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($hero['description']); ?></textarea>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Texto Botão 1</label>
                <input type="text" name="cta_button1" class="form-control" value="<?php echo htmlspecialchars($hero['cta_button1']); ?>">
            </div>
            <div class="form-group">
                <label>Texto Botão 2</label>
                <input type="text" name="cta_button2" class="form-control" value="<?php echo htmlspecialchars($hero['cta_button2']); ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar Conteúdo</button>
    </form>
</div>

<div class="card">
    <div class="card-title">🖼️ Imagem de Fundo do Hero</div>
    <p style="font-size: 0.85rem; color: #666; margin-bottom: 15px;">Esta imagem será exibida se o "Tipo de Fundo" estiver configurado como "Imagem" no menu de cores.</p>
    <form action="process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="upload_hero_image">
        <div class="form-group">
            <input type="file" name="hero_image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-secondary">📤 Upload Imagem</button>
    </form>
    
    <?php if ($hero['hero_image']): ?>
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Capa Atual:</label>
            <img src="../uploads/<?php echo $hero['hero_image']; ?>" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
