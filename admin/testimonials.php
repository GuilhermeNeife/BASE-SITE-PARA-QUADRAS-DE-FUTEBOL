<?php 
require_once 'header.php'; 
$testimonials = fetch_all(query("SELECT * FROM testimonials ORDER BY `order` ASC"));
?>

<div class="card" style="background: #f0f7f4; border: 1px solid #c3e6cb;">
    <div class="card-title" style="color: #155724;">⚙️ Configuração da Seção</div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_testimonials_visibility">
        <div style="display: flex; align-items: center; gap: 15px;">
            <label class="switch" style="position: relative; display: inline-block; width: 44px; height: 22px;">
                <input type="checkbox" name="show_testimonials" <?php echo ($site['show_testimonials'] ?? 1) ? 'checked' : ''; ?> style="opacity: 0; width: 0; height: 0;">
                <span class="slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
            </label>
            <span style="font-weight: 600;">Exibir seção de depoimentos no site</span>
            <button type="submit" class="btn btn-primary" style="margin-left: auto; padding: 8px 15px; font-size: 13px;">Salvar Preferência</button>
        </div>
    </form>
</div>

<style>
    .switch input:checked + .slider { background-color: #2d6a4f; }
    .slider:before {
        position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px;
        background-color: white; transition: .4s; border-radius: 50%;
    }
    .switch input:checked + .slider:before { transform: translateX(22px); }
</style>

<?php foreach($testimonials as $t): ?>
<div class="card">
    <div class="card-title">⭐ Depoimento de <?php echo htmlspecialchars($t['author_name']); ?></div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_testimonial">
        <input type="hidden" name="test_id" value="<?php echo $t['id']; ?>">
        
        <div class="form-group">
            <label>Avaliação (Estrelas)</label>
            <select name="stars" class="form-control">
                <?php for($i=1; $i<=5; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $t['stars'] == $i ? 'selected' : ''; ?>><?php echo str_repeat('⭐', $i); ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Texto do Depoimento</label>
            <textarea name="text" class="form-control"><?php echo htmlspecialchars($t['text']); ?></textarea>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Nome do Autor</label>
                <input type="text" name="author_name" class="form-control" value="<?php echo htmlspecialchars($t['author_name']); ?>">
            </div>
            <div class="form-group">
                <label>Cargo/Função</label>
                <input type="text" name="author_role" class="form-control" value="<?php echo htmlspecialchars($t['author_role']); ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Atualizar</button>
    </form>
</div>
<?php endforeach; ?>

<?php require_once 'footer.php'; ?>
