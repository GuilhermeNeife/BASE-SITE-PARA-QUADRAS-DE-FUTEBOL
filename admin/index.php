<?php require_once 'header.php'; ?>

<div class="card">
    <div class="card-title">🏠 Configurações do Site</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_site_settings">
        
        <div class="form-group">
            <label>Título do Site</label>
            <input type="text" name="site_title" class="form-control" value="<?php echo htmlspecialchars($site['site_title']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Descrição do Site (SEO)</label>
            <textarea name="site_description" class="form-control"><?php echo htmlspecialchars($site['site_description']); ?></textarea>
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label>Texto da Logo</label>
                <input type="text" name="logo_text" class="form-control" value="<?php echo htmlspecialchars($site['logo_text']); ?>">
            </div>
            <div class="form-group">
                <label>Slogan da Logo</label>
                <input type="text" name="logo_tagline" class="form-control" value="<?php echo htmlspecialchars($site['logo_tagline']); ?>">
            </div>
        </div>
        
        <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="use_logo_image" id="use_logo" <?php echo $site['use_logo_image'] ? 'checked' : ''; ?>>
            <label for="use_logo" style="margin-bottom: 0;">Usar imagem como logo (em vez de texto)</label>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
    </form>
</div>

<div class="card">
    <div class="card-title">🖼️ Logo do Site</div>
    <form action="process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="upload_logo">
        
        <div class="form-group">
            <label>Nova Logo</label>
            <input type="file" name="logo_image" class="form-control" accept="image/*">
        </div>
        
        <?php if ($site['logo_image']): ?>
            <div style="margin-bottom: 20px; padding: 15px; background: #f1f1f1; border-radius: 8px; display: inline-block;">
                <p style="font-size: 12px; margin-bottom: 10px; font-weight: 600;">Logo Atual:</p>
                <img src="../uploads/<?php echo $site['logo_image']; ?>" style="max-height: 60px;">
            </div>
        <?php endif; ?>
        
        <br>
        <button type="submit" class="btn btn-secondary">📤 Fazer Upload</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
