<?php 
require_once 'header.php'; 
$stats = fetch_all(query("SELECT * FROM statistics ORDER BY `order`"));
?>

<?php foreach($stats as $s): ?>
<div class="card">
    <div class="card-title">📊 Estatística #<?php echo $s['id']; ?></div>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="update_stat">
        <input type="hidden" name="stat_id" value="<?php echo $s['id']; ?>">
        
        <div class="grid-2">
            <div class="form-group">
                <label>Número/Valor</label>
                <input type="text" name="stat_number" class="form-control" value="<?php echo htmlspecialchars($s['stat_number']); ?>">
            </div>
            <div class="form-group">
                <label>Rótulo/Texto</label>
                <input type="text" name="stat_label" class="form-control" value="<?php echo htmlspecialchars($s['stat_label']); ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Salvar</button>
    </form>
</div>
<?php endforeach; ?>

<?php require_once 'footer.php'; ?>
