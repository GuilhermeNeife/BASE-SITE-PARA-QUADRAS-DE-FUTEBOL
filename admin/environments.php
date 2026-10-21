<?php 
require_once 'header.php'; 
$environments = fetch_all(query("SELECT * FROM environments ORDER BY `order` ASC"));
?>

<div class="card">
    <div class="card-title">➕ Adicionar Novo Ambiente</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="create_environment">
        <div class="grid-2">
            <div class="form-group">
                <label>Nome do Ambiente</label>
                <input type="text" name="name" class="form-control" placeholder="Ex: Quadra 1" required>
            </div>
            <div class="form-group">
                <label>Ícone (Emoji)</label>
                <input type="text" name="icon" class="form-control" placeholder="Ex: ⚽" required>
            </div>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="description" class="form-control" placeholder="Descreva o ambiente..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">➕ Criar Ambiente</button>
    </form>
</div>

<div class="card">
    <div class="card-title">🔃 Reordenar Ambientes</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="reorder_environments">
        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
            <?php foreach($environments as $e): ?>
                <div style="display: flex; align-items: center; gap: 15px; background: #f8f9fa; padding: 10px 15px; border-radius: 8px; border: 1px solid #ddd;">
                    <span style="font-size: 20px;"><?php echo $e['icon']; ?></span>
                    <span style="flex: 1; font-weight: 600;"><?php echo htmlspecialchars($e['name']); ?></span>
                    <input type="number" name="orders[<?php echo $e['id']; ?>]" value="<?php echo $e['order']; ?>" class="form-control" style="width: 70px; padding: 5px 10px;">
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-secondary">💾 Salvar Ordem</button>
    </form>
</div>

<?php foreach($environments as $e): ?>
<div class="card" id="env-<?php echo $e['id']; ?>">
    <div class="card-title" style="justify-content: space-between;">
        <span>🏢 Editar: <?php echo htmlspecialchars($e['name']); ?></span>
        <form action="process.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este ambiente e todas as suas fotos?');" style="display:inline;">
            <input type="hidden" name="action" value="delete_environment">
            <input type="hidden" name="env_id" value="<?php echo $e['id']; ?>">
            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Excluir</button>
        </form>
    </div>
    
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_environment">
        <input type="hidden" name="env_id" value="<?php echo $e['id']; ?>">
        
        <div class="grid-2">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($e['name']); ?>">
            </div>
            <div class="form-group">
                <label>Ícone</label>
                <input type="text" name="icon" class="form-control" value="<?php echo htmlspecialchars($e['icon']); ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($e['description']); ?></textarea>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="visible" id="vis_<?php echo $e['id']; ?>" <?php echo $e['visible'] ? 'checked' : ''; ?>>
            <label for="vis_<?php echo $e['id']; ?>" style="margin-bottom: 0;">Visível no site</label>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar Dados</button>
    </form>

    <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">

    <div class="grid-2">
        <!-- CAPA -->
        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 10px;">🖼️ Imagem de Capa</label>
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_environment_cover">
                <input type="hidden" name="env_id" value="<?php echo $e['id']; ?>">
                <input type="file" name="cover_image" class="form-control" style="margin-bottom: 10px;" accept="image/*">
                <button type="submit" class="btn btn-secondary btn-sm">📤 Upload Capa</button>
            </form>
            <?php if ($e['cover_image']): ?>
                <img src="../uploads/<?php echo $e['cover_image']; ?>" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-top: 10px; border: 1px solid #ddd;">
            <?php endif; ?>
        </div>

        <!-- GALERIA -->
        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 10px;">📸 Galeria de Fotos</label>
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_environment_images">
                <input type="hidden" name="env_id" value="<?php echo $e['id']; ?>">
                <input type="file" name="environment_images[]" class="form-control" multiple style="margin-bottom: 10px;" accept="image/*">
                <button type="submit" class="btn btn-secondary btn-sm">📤 Adicionar Fotos</button>
            </form>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 15px;">
                <?php 
                $images = fetch_all(query("SELECT * FROM environment_images WHERE environment_id = " . $e['id'] . " ORDER BY `order` ASC"));
                foreach($images as $img): ?>
                    <div style="position: relative; aspect-ratio: 1; border-radius: 4px; overflow: hidden; border: 1px solid #ddd;">
                        <img src="../uploads/<?php echo $img['image_path']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <form action="process.php" method="POST" style="position: absolute; top: 2px; right: 2px;">
                            <input type="hidden" name="action" value="delete_environment_image">
                            <input type="hidden" name="img_id" value="<?php echo $img['id']; ?>">
                            <button type="submit" style="background: rgba(255,0,0,0.8); color: white; border: none; width: 20px; height: 20px; border-radius: 50%; cursor: pointer; font-size: 10px;">✕</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once 'footer.php'; ?>
